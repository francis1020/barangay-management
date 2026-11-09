<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'residents' => Resident::active()->count(),
            'households' => Household::count(),
            'puroks' => Purok::count(),
            'voters' => Resident::active()->voters()->count(),
        ];

        $demographics = [
            'male' => Resident::active()->where('sex', 'male')->count(),
            'female' => Resident::active()->where('sex', 'female')->count(),
            'other' => Resident::active()->where('sex', 'other')->count(),
            'seniors' => Resident::active()->where('is_senior_citizen', true)->count(),
            'pwds' => Resident::active()->where('is_pwd', true)->count(),
        ];

        // Chart Data: Comparative Distribution by Purok
        $purokData = Purok::withCount([
            'residents' => function($query) {
                $query->active();
            },
            'households'
        ])->orderBy('name')->get();

        return view('dashboard', compact('stats', 'demographics', 'purokData'));
    }
}
