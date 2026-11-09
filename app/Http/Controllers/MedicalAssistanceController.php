<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\MedicalAssistance;
use Illuminate\Http\Request;

class MedicalAssistanceController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $assistances = MedicalAssistance::with('resident')
            ->when($request->search, function ($q) use ($request) {
                $search = trim($request->search);
                $terms = explode(' ', $search);

                $q->where(function ($query) use ($terms, $search) {

                    // 🔹 Resident name search
                    $query->whereHas('resident', function ($q2) use ($terms, $search) {

                        // Match full name (first + last)
                        $q2->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);

                        // Also match individual terms
                        foreach ($terms as $term) {
                            $q2->orWhere('first_name', 'like', "%{$term}%")
                                ->orWhere('last_name', 'like', "%{$term}%");
                        }
                    });

                    // 🔹 Assistance type search (properly grouped)
                    $query->orWhere('assistance_type', 'like', "%{$search}%");
                });
            })
            ->latest('date_given')
            ->paginate($limit)
            ->withQueryString();

        return view('medical-assistances.index', compact('assistances'));
    }

    public function create()
    {
        return view('medical-assistances.create');
    }

    public function edit($id)
    {
        $assistance = MedicalAssistance::with('resident')->findOrFail($id);
        return view('medical-assistances.edit', compact('assistance'));
    }

    public function store(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'assistance_type' => 'required|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'date_given' => 'nullable|date',
            'provider' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $resident->medicalAssistances()->create($validated);

        return back()->with('success', 'Medical assistance record added successfully.');
    }

    public function storeGlobal(Request $request)
    {
        $request->validate(['resident_id' => 'required|exists:residents,id']);
        $resident = Resident::findOrFail($request->resident_id);
        $this->store($request, $resident);
        return redirect()->route('medical-assistances.index')->with('success', 'Medical assistance record added successfully.');
    }

    public function update(Request $request, MedicalAssistance $medicalAssistance)
    {
        $validated = $request->validate([
            'assistance_type' => 'required|string|max:100',
            'amount' => 'nullable|numeric',
            'provider' => 'nullable|string|max:100',
            'date_given' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $medicalAssistance->update($validated);

        return redirect()->route('medical-assistances.index')->with('success', 'Medical assistance record updated successfully.');
    }

    public function destroy(\App\Models\MedicalAssistance $medicalAssistance)
    {
        $medicalAssistance->delete();
        return back()->with('success', 'Medical assistance record deleted successfully.');
    }
}
