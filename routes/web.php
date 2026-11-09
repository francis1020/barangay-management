<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\PurokController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\ResidentHealthRecordController;
use App\Http\Controllers\ResidentVaccinationController;
use App\Http\Controllers\MedicalAssistanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Residents
    Route::middleware('role:view_residents')->group(function () {
        Route::get('residents', [ResidentController::class, 'index'])->name('residents.index');
        Route::get('residents/search', [ResidentController::class, 'search'])->name('residents.search');
        Route::get('residents/create', [ResidentController::class, 'create'])->name('residents.create')->middleware('role:create_residents');
        Route::post('residents', [ResidentController::class, 'store'])->name('residents.store')->middleware('role:create_residents');
        Route::get('residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
        Route::get('residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit')->middleware('role:edit_residents');
        Route::patch('residents/{resident}', [ResidentController::class, 'update'])->name('residents.update')->middleware('role:edit_residents');
        Route::delete('residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy')->middleware('role:delete_residents');
    });

    // Households
    Route::middleware('role:view_households')->group(function () {
        Route::get('households/search', [HouseholdController::class, 'search'])->name('households.search');
        Route::get('households', [HouseholdController::class, 'index'])->name('households.index');

        Route::middleware('role:create_households')->group(function () {
            Route::get('households/create', [HouseholdController::class, 'create'])->name('households.create');
            Route::post('households', [HouseholdController::class, 'store'])->name('households.store');
        });

        Route::middleware('role:edit_households')->group(function () {
            Route::get('households/{household}/edit', [HouseholdController::class, 'edit'])->name('households.edit');
            Route::patch('households/{household}', [HouseholdController::class, 'update'])->name('households.update');
        });

        Route::delete('households/{household}', [HouseholdController::class, 'destroy'])->name('households.destroy')->middleware('role:delete_households');

        // Match {household} after static routes like 'create'
        Route::get('households/{household}', [HouseholdController::class, 'show'])->name('households.show');

    });

    // Puroks
    Route::middleware('role:view_puroks')->group(function () {
        Route::get('puroks', [PurokController::class, 'index'])->name('puroks.index');
        Route::get('puroks/create', [PurokController::class, 'create'])->name('puroks.create')->middleware('role:create_puroks');
        Route::post('puroks', [PurokController::class, 'store'])->name('puroks.store')->middleware('role:create_puroks');
        Route::get('puroks/{purok}', [PurokController::class, 'show'])->name('puroks.show');
        Route::get('puroks/{purok}/edit', [PurokController::class, 'edit'])->name('puroks.edit')->middleware('role:edit_puroks');
        Route::patch('puroks/{purok}', [PurokController::class, 'update'])->name('puroks.update')->middleware('role:edit_puroks');
        Route::delete('puroks/{purok}', [PurokController::class, 'destroy'])->name('puroks.destroy')->middleware('role:delete_puroks');
    });

    // Family Members (Linked to Residents/Households)
    Route::middleware('role:view_residents')->group(function () {
        Route::get('family-members', [FamilyMemberController::class, 'index'])->name('family-members.index');
        Route::get('family-members/{resident}/connections', [FamilyMemberController::class, 'connections'])->name('family-members.connections')->whereNumber('resident');
        Route::get('family-members/{resident}', [FamilyMemberController::class, 'show'])->name('family-members.show')->whereNumber('resident');
    });

    Route::middleware('role:edit_residents')->group(function () {
        Route::get('family-members/create', [FamilyMemberController::class, 'create'])->name('family-members.create');
        Route::get('family-members/{resident}/create', [FamilyMemberController::class, 'create'])->name('family-members.create-for')->whereNumber('resident');
        Route::get('family-members/{familyMember}/edit', [FamilyMemberController::class, 'edit'])->name('family-members.edit');
        Route::post('family-members', [FamilyMemberController::class, 'store'])->name('family-members.store');
        Route::patch('family-members/{familyMember}', [FamilyMemberController::class, 'update'])->name('family-members.update');
        Route::delete('family-members/{familyMember}', [FamilyMemberController::class, 'destroy'])->name('family-members.destroy');
    });

    // Health, Vaccination & Medical Assistance
    Route::middleware('role:view_residents')->group(function () {
        Route::get('health-records', [ResidentHealthRecordController::class, 'index'])->name('health-records.index');
        Route::get('vaccinations', [ResidentVaccinationController::class, 'index'])->name('vaccinations.index');
        Route::get('medical-assistances', [MedicalAssistanceController::class, 'index'])->name('medical-assistances.index');
    });

    Route::middleware('role:edit_residents')->group(function () {
        Route::get('health-records/create', [ResidentHealthRecordController::class, 'create'])->name('health-records.create');
        Route::get('health-records/{healthRecord}/edit', [ResidentHealthRecordController::class, 'edit'])->name('health-records.edit');
        Route::post('residents/{resident}/health-record', [ResidentHealthRecordController::class, 'store'])->name('health-records.store');
        Route::post('health-records', [ResidentHealthRecordController::class, 'storeGlobal'])->name('health-records.store-global');
        Route::patch('health-records/{healthRecord}', [ResidentHealthRecordController::class, 'update'])->name('health-records.update');
        Route::delete('health-records/{healthRecord}', [ResidentHealthRecordController::class, 'destroy'])->name('health-records.destroy');
        
        Route::get('vaccinations/create', [ResidentVaccinationController::class, 'create'])->name('vaccinations.create');
        Route::get('vaccinations/{vaccination}/edit', [ResidentVaccinationController::class, 'edit'])->name('vaccinations.edit');
        Route::post('residents/{resident}/vaccinations', [ResidentVaccinationController::class, 'store'])->name('vaccinations.store');
        Route::post('vaccinations', [ResidentVaccinationController::class, 'storeGlobal'])->name('vaccinations.store-global');
        Route::patch('vaccinations/{vaccination}', [ResidentVaccinationController::class, 'update'])->name('vaccinations.update');
        Route::delete('vaccinations/{vaccination}', [ResidentVaccinationController::class, 'destroy'])->name('vaccinations.destroy');
        
        Route::get('medical-assistances/create', [MedicalAssistanceController::class, 'create'])->name('medical-assistances.create');
        Route::get('medical-assistances/{medicalAssistance}/edit', [MedicalAssistanceController::class, 'edit'])->name('medical-assistances.edit');
        Route::post('residents/{resident}/medical-assistances', [MedicalAssistanceController::class, 'store'])->name('medical-assistances.store');
        Route::post('medical-assistances', [MedicalAssistanceController::class, 'storeGlobal'])->name('medical-assistances.store-global');
        Route::patch('medical-assistances/{medicalAssistance}', [MedicalAssistanceController::class, 'update'])->name('medical-assistances.update');
        Route::delete('medical-assistances/{medicalAssistance}', [MedicalAssistanceController::class, 'destroy'])->name('medical-assistances.destroy');
    });

    // User & Role Management (Super Admin only)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

});

require __DIR__ . '/auth.php';