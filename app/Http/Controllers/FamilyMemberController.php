<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\Resident;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);

        // Get distinct residents who have at least one family connection
        $residents = Resident::whereHas('familyMembers')
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->withCount('familyMembers')
            ->orderBy('last_name')
            ->paginate($limit)
            ->withQueryString();

        return view('family-members.index', compact('residents'));
    }

    public function create(Request $request, Resident $resident = null)
    {
        $preResident = $resident; // from path: /family-members/{resident}/create

        // fallback to query param for backwards compat
        if (!$preResident && $request->resident_id) {
            $preResident = Resident::find($request->resident_id);
        }

        $existingConnections = collect();
        if ($preResident) {
            $existingConnections = FamilyMember::with('relatedResident')
                ->where('resident_id', $preResident->id)
                ->latest()
                ->get();
        }

        return view('family-members.create', compact('preResident', 'existingConnections'));
    }

    public function show(Resident $resident)
    {
        $familyMembers = FamilyMember::with('relatedResident')
            ->where('resident_id', $resident->id)
            ->latest()
            ->get();

        return view('family-members.show', compact('resident', 'familyMembers'));
    }

    public function connections(Resident $resident)
    {
        $connections = FamilyMember::with('relatedResident')
            ->where('resident_id', $resident->id)
            ->latest()
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'relationship' => $c->relationship,
                'edit_url' => route('family-members.edit', $c->id),
                'destroy_url' => route('family-members.destroy', $c->id),
                'related_name' => $c->relatedResident->full_name,
            ]);

        return response()->json($connections);
    }

    public function edit($id)
    {
        $familyMember = FamilyMember::with(['resident', 'relatedResident'])->findOrFail($id);
        return view('family-members.edit', compact('familyMember'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'resident_id' => ['required', 'exists:residents,id'],
            'connections' => ['required', 'array', 'min:1'],
            'connections.*.related_resident_id' => ['required', 'exists:residents,id'],
            'connections.*.relationship' => ['required', 'string', 'max:50'],
        ]);

        $count = 0;
        foreach ($request->connections as $connection) {
            // 1. Create primary connection
            $exists = FamilyMember::where('resident_id', $request->resident_id)
                ->where('related_resident_id', $connection['related_resident_id'])
                ->exists();

            if (!$exists) {
                FamilyMember::create([
                    'resident_id' => $request->resident_id,
                    'related_resident_id' => $connection['related_resident_id'],
                    'relationship' => $connection['relationship'],
                ]);
                $count++;
            }

            // 2. Create reciprocal (reverse) connection with null relationship
            $reverseExists = FamilyMember::where('resident_id', $connection['related_resident_id'])
                ->where('related_resident_id', $request->resident_id)
                ->exists();

            if (!$reverseExists) {
                FamilyMember::create([
                    'resident_id' => $connection['related_resident_id'],
                    'related_resident_id' => $request->resident_id,
                    'relationship' => null, // To be defined by the other resident
                ]);
            }
        }

        return redirect()->route('family-members.index')->with('success', "Successfully connected {$count} family member(s).");
    }

    public function update(Request $request, FamilyMember $familyMember)
    {
        $data = $request->validate([
            'relationship' => ['required', 'string', 'max:50'],
        ]);

        $familyMember->update($data);

        return redirect()->route('family-members.index')->with('success', 'Relationship defined successfully.');
    }

    public function destroy(FamilyMember $familyMember)
    {
        $familyMember->delete();
        return back()->with('success', 'Family member connection removed.');
    }
}
