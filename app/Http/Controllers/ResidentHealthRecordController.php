<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\ResidentHealthRecord;
use Illuminate\Http\Request;

class ResidentHealthRecordController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $records = \App\Models\ResidentHealthRecord::with('resident')
            ->when($request->search, function ($q) use ($request) {

                $search = trim($request->search);
                $terms = explode(' ', $search);

                $q->whereHas('resident', function ($q2) use ($terms, $search) {

                    // Match full name (first + last)
                    $q2->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);

                    // Also match individual terms
                    foreach ($terms as $term) {
                        $q2->orWhere('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%");
                    }
                });
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        return view('health-records.index', compact('records'));
    }

    public function create()
    {
        return view('health-records.create');
    }

    public function edit($id)
    {
        $healthRecord = \App\Models\ResidentHealthRecord::with('resident')->findOrFail($id);
        return view('health-records.edit', compact('healthRecord'));
    }

    public function store(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'blood_type' => 'nullable|string|max:10',
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
        ]);

        $resident->healthRecord()->updateOrCreate(
            ['resident_id' => $resident->id],
            $validated
        );

        return back()->with('success', 'Health record updated successfully.');
    }

    public function storeGlobal(Request $request)
    {
        $request->validate(['resident_id' => 'required|exists:residents,id']);
        $resident = Resident::findOrFail($request->resident_id);
        $this->store($request, $resident);
        return redirect()->route('health-records.index')->with('success', 'Health record added successfully.');
    }

    public function update(Request $request, ResidentHealthRecord $healthRecord)
    {
        $validated = $request->validate([
            'blood_type' => 'nullable|string|max:5',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
        ]);

        $healthRecord->update($validated);

        return redirect()->route('health-records.index')->with('success', 'Health record updated successfully.');
    }

    public function destroy(\App\Models\ResidentHealthRecord $healthRecord)
    {
        $healthRecord->delete();
        return back()->with('success', 'Health record removed successfully.');
    }
}
