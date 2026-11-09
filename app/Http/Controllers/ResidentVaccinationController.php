<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\ResidentVaccination;
use Illuminate\Http\Request;

class ResidentVaccinationController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $vaccinations = ResidentVaccination::with('resident')
            ->when($request->search, function ($q) use ($request) {

                $search = trim($request->search);
                $terms = explode(' ', $search);

                $q->where(function ($query) use ($terms, $search) {

                    // 🔹 Resident name search
                    $query->whereHas('resident', function ($q2) use ($terms, $search) {

                        // Full name match
                        $q2->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);

                        // Individual terms
                        foreach ($terms as $term) {
                            $q2->orWhere('first_name', 'like', "%{$term}%")
                                ->orWhere('last_name', 'like', "%{$term}%");
                        }
                    });

                    // 🔹 Vaccine name search (properly grouped)
                    $query->orWhere('vaccine_name', 'like', "%{$search}%");
                });
            })
            ->latest('date_administered')
            ->paginate($limit)
            ->withQueryString();

        return view('vaccinations.index', compact('vaccinations'));
    }

    public function create()
    {
        return view('vaccinations.create');
    }

    public function edit($id)
    {
        $vaccination = ResidentVaccination::with('resident')->findOrFail($id);
        return view('vaccinations.edit', compact('vaccination'));
    }

    public function store(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'vaccine_type' => 'nullable|string|max:255',
            'dose_number' => 'nullable|integer|min:1',
            'date_administered' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $resident->vaccinations()->create($validated);

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Vaccination record added successfully.');
    }

    public function storeGlobal(Request $request)
    {
        $request->validate(['resident_id' => 'required|exists:residents,id']);
        $resident = Resident::findOrFail($request->resident_id);
        $this->store($request, $resident);
        return redirect()->route('vaccinations.index')->with('success', 'Vaccination record added successfully.');
    }

    public function update(Request $request, ResidentVaccination $vaccination)
    {
        $validated = $request->validate([
            'vaccine_name' => 'required|string|max:100',
            'vaccine_type' => 'nullable|string|max:100',
            'dose_number' => 'nullable|integer|min:1',
            'date_administered' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $vaccination->update($validated);

        return redirect()->route('vaccinations.index')->with('success', 'Vaccination record updated successfully.');
    }

    public function destroy(\App\Models\ResidentVaccination $vaccination)
    {
        $vaccination->delete();
        return back()->with('success', 'Vaccination record deleted successfully.');
    }
}
