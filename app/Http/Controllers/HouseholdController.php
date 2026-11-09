<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->query('q');
        
        if (strlen($term) < 1) {
            return response()->json([]);
        }

        $households = Household::with(['head', 'purok'])
            ->where('household_no', 'like', "%{$term}%")
            ->orWhere('address', 'like', "%{$term}%")
            ->orderBy('household_no')
            ->limit(15)
            ->get();

        return response()->json($households->map(fn($h) => [
            'id' => $h->id,
            'text' => "HH #{$h->household_no} - " . ($h->head ? $h->head->full_name : 'No Head Set'),
            'address' => $h->address,
            'purok' => $h->purok ? $h->purok->name : 'N/A',
        ]));
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $households = Household::with(['purok', 'head', 'residents'])
            ->when($request->purok_id, fn($q) => $q->where('purok_id', $request->purok_id))
            ->when($request->search, fn($q) => $q->where('household_no', 'like', "%{$request->search}%")
                ->orWhere('address', 'like', "%{$request->search}%"))
            ->orderBy('household_no')
            ->paginate($limit)->withQueryString();

        $puroks = Purok::orderBy('name')->get();
        
        $stats = [
            'total'   => Household::count(),
            'members' => Resident::active()->whereNotNull('household_id')->count(),
            'seniors' => Resident::active()->where('is_senior_citizen', true)->count(),
            'pwds'    => Resident::active()->where('is_pwd', true)->count(),
        ];

        return view('households.index', compact('households', 'puroks', 'stats'));
    }

    public function create()
    {
        $puroks    = Purok::orderBy('name')->get();
        $residents = Resident::active()->orderBy('last_name')->get();
        return view('households.create', compact('puroks', 'residents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purok_id'         => ['required', 'exists:puroks,id'],
            'address'          => ['required', 'string', 'max:255'],
            'ownership_status' => ['required', 'in:owned,rented,shared,informal_settler'],
            'housing_type'     => ['required', 'in:concrete,semi_concrete,wood,makeshift'],
            'income_class'     => ['nullable', 'in:poor,low_income,middle,high'],
            'monthly_income'   => ['nullable', 'numeric', 'min:0'],
            'resident_ids'      => ['nullable', 'array'],
            'resident_ids.*'    => ['exists:residents,id'],
        ]);

        $household = Household::create($data);

        // Assign residents to this household
        if ($request->has('resident_ids')) {
            Resident::whereIn('id', $request->resident_ids)
                ->update(['household_id' => $household->id]);
        }

        return redirect()->route('households.show', $household)
            ->with('success', 'Household created.');
    }

    public function show(Household $household)
    {
        $household->load(['purok', 'head', 'residents.voterInfo']);
        
        $stats = [
            'male'    => $household->residents->where('sex', 'male')->count(),
            'female'  => $household->residents->where('sex', 'female')->count(),
            'pwds'    => $household->residents->where('is_pwd', true)->count(),
            'seniors' => $household->residents->where('is_senior_citizen', true)->count(),
            'profiling' => $household->demographics,
        ];

        return view('households.show', compact('household', 'stats'));
    }

    public function edit(Household $household)
    {
        $puroks    = Purok::orderBy('name')->get();
        $residents = Resident::active()->where('household_id', $household->id)->orderBy('last_name')->get();
        return view('households.edit', compact('household', 'puroks', 'residents'));
    }

    public function update(Request $request, Household $household)
    {
        $data = $request->validate([
            'purok_id'          => ['required', 'exists:puroks,id'],
            'head_resident_id'  => ['nullable', 'exists:residents,id'],
            'address'           => ['required', 'string', 'max:255'],
            'ownership_status'  => ['required', 'in:owned,rented,shared,informal_settler'],
            'housing_type'      => ['required', 'in:concrete,semi_concrete,wood,makeshift'],
            'income_class'      => ['nullable', 'in:poor,low_income,middle,high'],
            'monthly_income'    => ['nullable', 'numeric', 'min:0'],
            'resident_ids'       => ['nullable', 'array'],
            'resident_ids.*'     => ['exists:residents,id'],
        ]);

        $household->update($data);

        // Sync residents: Remove old ones not in the list, add new ones
        if ($request->has('resident_ids')) {
            // Residents to remove (current members NOT in the new list)
            Resident::where('household_id', $household->id)
                ->whereNotIn('id', $request->resident_ids)
                ->update(['household_id' => null]);

            // Residents to add/keep
            Resident::whereIn('id', $request->resident_ids)
                ->update(['household_id' => $household->id]);
        } else {
            // If the list is empty, remove all current members
            Resident::where('household_id', $household->id)
                ->update(['household_id' => null]);
        }

        return redirect()->route('households.show', $household)
            ->with('success', 'Household updated.');
    }

    public function destroy(Household $household)
    {
        $household->delete();
        return redirect()->route('households.index')
            ->with('success', 'Household deleted.');
    }
}