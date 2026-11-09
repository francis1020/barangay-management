@extends('layouts.app')

@section('title', 'Edit Health Record')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('health-records.index') }}">Health Records</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit Record</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0 fw-bold">Edit Health Record</h4>
                <p class="text-muted small mb-0">Update health profile for {{ $healthRecord->resident->full_name }}.</p>
            </div>
            <a href="{{ route('health-records.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('health-records.update', $healthRecord->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label fw-bold">Resident</label>
                        <input type="text" class="form-control bg-light" value="{{ $healthRecord->resident->full_name }}" readonly>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary">Vitals & Background</h6>
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Blood Type</label>
                            <input type="text" name="blood_type" class="form-control" value="{{ $healthRecord->blood_type }}" placeholder="e.g. O+, A-, B+">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control" value="{{ $healthRecord->height }}" placeholder="170.5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="{{ $healthRecord->weight }}" placeholder="65.0">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary">Medical Information</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Medical Conditions</label>
                            <textarea name="medical_conditions" class="form-control" rows="3" placeholder="e.g. Hypertension, Asthma, Type 2 Diabetes...">{{ $healthRecord->medical_conditions }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Allergies</label>
                            <textarea name="allergies" class="form-control" rows="3" placeholder="e.g. Penicillin, Peanuts, Seafood...">{{ $healthRecord->allergies }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Medications</label>
                            <textarea name="medications" class="form-control" rows="3" placeholder="e.g. Losartan 50mg, Maintenance meds...">{{ $healthRecord->medications }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('health-records.index') }}" class="btn btn-light px-4 border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2 me-1"></i> Update Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
