<?php

namespace App\Http\Controllers;

use App\Models\Purok;
use App\Models\Resident;
use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->query('q');
        $currentHouseholdId = $request->query('household_id');
        $excludeIds = (array) $request->query('exclude_ids', []);

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $residents = Resident::active()
            ->with('household')
            ->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%");
            })
            ->whereNotIn('id', $excludeIds)
            ->limit(15)
            ->get();

        // Sort by same household priority
        $results = $residents->sortBy(function ($resident) use ($currentHouseholdId) {
            return $resident->household_id == $currentHouseholdId ? 0 : 1;
        })->values();

        return response()->json($results->map(fn($r) => [
            'id' => $r->id,
            'text' => $r->full_name . ' (' . $r->age . ' yrs)',
            'household' => $r->household_id == $currentHouseholdId
                ? 'Same Household'
                : ($r->household ? $r->household->household_no : 'No Household'),
            'is_same_household' => $r->household_id == $currentHouseholdId,
        ]));
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        $residents = Resident::with(['purok', 'household', 'voterInfo'])
            ->active()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->purok_id, fn($q) => $q->byPurok($request->purok_id))
            ->when($request->sex, fn($q) => $q->where('sex', $request->sex))
            ->when($request->voter_status === 'registered', fn($q) => $q->voters())
            ->when($request->is_senior, fn($q) => $q->seniorCitizens())
            ->when($request->is_pwd, fn($q) => $q->where('is_pwd', true))
            ->when($request->age_from, fn($q) => $q->whereDate('birthdate', '<=', now()->subYears($request->age_from)))
            ->when($request->age_to, fn($q) => $q->whereDate('birthdate', '>=', now()->subYears($request->age_to)))
            ->paginate($limit)->withQueryString();

        $puroks = Purok::orderBy('name')->get();

        $stats = [
            'total' => Resident::active()->count(),
            'male' => Resident::active()->where('sex', 'male')->count(),
            'female' => Resident::active()->where('sex', 'female')->count(),
            'seniors' => Resident::active()->seniorCitizens()->count(),
            'pwds' => Resident::active()->where('is_pwd', true)->count(),
        ];

        return view('residents.index', compact('residents', 'puroks', 'stats'));
    }

    public function create()
    {
        $puroks = Purok::orderBy('name')->get();
        return view('residents.create', compact('puroks'));
    }

    public function store(StoreResidentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('residents/photos', 'public');
        }

        // Auto-tag senior citizen
        $age = Carbon::parse($data['birthdate'])->age;
        $data['is_senior_citizen'] = $age >= 60;

        $resident = Resident::create($data);

        // Create voter info record shell
        if ($request->is_registered) {
            $resident->voterInfo()->create([
                'is_registered' => true,
                'precinct_no' => $request->precinct_no,
                'voters_id_no' => $request->voters_id_no,
                'registration_date' => $request->registration_date,
                'voting_center' => $request->voting_center,
            ]);
        } else {
            $resident->voterInfo()->create(['is_registered' => false]);
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $resident->documents()->create([
                    'document_type' => $request->document_types[$index] ?? 'other',
                    'file_path' => $file->store("residents/{$resident->id}/docs", 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident registered successfully.');
    }

    public function show(Resident $resident)
    {
        $resident->load(['purok', 'household', 'voterInfo', 'documents', 'familyMembers.relatedResident', 'healthRecord', 'vaccinations', 'medicalAssistances']);

        // Get family member resident IDs (including the resident themselves)
        $familyResidentIds = $resident->familyMembers->pluck('related_resident_id')->push($resident->id)->filter();

        $familyQuery = Resident::whereIn('id', $familyResidentIds);

        $stats = [
            'male' => $familyQuery->clone()->active()->where('sex', 'male')->count(),
            'female' => $familyQuery->clone()->active()->where('sex', 'female')->count(),
            'pwds' => $familyQuery->clone()->active()->where('is_pwd', true)->count(),
            'seniors' => $familyQuery->clone()->active()->where('is_senior_citizen', true)->count(),
        ];

        return view('residents.show', compact('resident', 'stats'));
    }

    public function edit(Resident $resident)
    {
        $puroks = Purok::orderBy('name')->get();
        return view('residents.edit', compact('resident', 'puroks'));
    }

    public function update(UpdateResidentRequest $request, Resident $resident)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($resident->profile_photo) {
                \Storage::disk('public')->delete($resident->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('residents/photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($resident->profile_photo) {
                \Storage::disk('public')->delete($resident->profile_photo);
            }
            $data['profile_photo'] = null;
        }

        // Auto-tag senior citizen
        $age = Carbon::parse($data['birthdate'])->age;
        $data['is_senior_citizen'] = $age >= 60;

        $resident->update($data);

        // Update voter info
        $resident->voterInfo()->updateOrCreate([], [
            'is_registered' => $request->boolean('is_registered'),
            'precinct_no' => $request->precinct_no,
            'voters_id_no' => $request->voters_id_no,
            'registration_date' => $request->registration_date,
            'voting_center' => $request->voting_center,
        ]);

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();
        return redirect()->route('residents.index')
            ->with('success', 'Resident profile deleted.');
    }
}
