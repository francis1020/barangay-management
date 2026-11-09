<?php

namespace App\Http\Controllers;

use App\Models\Purok;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class PurokController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $puroks = Purok::withCount(['residents', 'households'])
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy('name')
            ->paginate($limit)->withQueryString();

        $stats = [
            'total_puroks'   => Purok::count(),
            'total_households' => Household::count(),
            'total_residents'  => Resident::active()->count(),
        ];

        // Fetch all puroks with coordinates for the map
        $allPuroks = Purok::whereNotNull('coordinates')->get(['id', 'name', 'coordinates', 'color']);

        return view('puroks.index', compact('puroks', 'stats', 'allPuroks'));
    }

    public function show(Request $request, Purok $purok)
    {
        $purok->loadCount(['residents', 'households']);
        
        $stats = [
            'seniors_count' => $purok->residents()->active()->where('is_senior_citizen', true)->count(),
            'pwds_count'    => $purok->residents()->active()->where('is_pwd', true)->count(),
        ];
        
        $residentLimit = $request->input('limit_residents', 10);
        $householdLimit = $request->input('limit_households', 10);

        $residents = $purok->residents()->active()->latest()->paginate($residentLimit, ['*'], 'residents_page')->withQueryString();
        $households = $purok->households()->with('head')->latest()->paginate($householdLimit, ['*'], 'households_page')->withQueryString();

        return view('puroks.show', compact('purok', 'residents', 'households', 'stats'));
    }

    public function create()
    {
        return view('puroks.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:puroks,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'coordinates' => ['nullable', 'string'],
            'color'       => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);
        
        if ($data['coordinates']) {
            $data['coordinates'] = json_decode($data['coordinates'], true);
        }
        Purok::create($data);
        return redirect()->route('puroks.index')->with('success', 'Purok added.');
    }

    public function edit(Purok $purok)
    {
        return view('puroks.edit', compact('purok'));
    }

    public function update(Request $request, Purok $purok)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', "unique:puroks,name,{$purok->id}"],
            'description' => ['nullable', 'string', 'max:255'],
            'coordinates' => ['nullable', 'string'],
            'color'       => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        if ($data['coordinates']) {
            $data['coordinates'] = json_decode($data['coordinates'], true);
        }
        $purok->update($data);
        return redirect()->route('puroks.index')->with('success', 'Purok updated.');
    }

    public function destroy(Purok $purok)
    {
        if ($purok->residents()->exists()) {
            return back()->with('error', 'Cannot delete a purok with residents.');
        }
        $purok->delete();
        return back()->with('success', 'Purok deleted.');
    }
}