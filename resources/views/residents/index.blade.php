@extends('layouts.app')

@section('title', 'Residents')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Residents</li>
@push('styles')
<style>
    .clickable-card {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .clickable-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        border-color: #dee2e6;
    }
    .active-card {
        border-color: var(--bs-primary) !important;
        background-color: rgba(13, 110, 253, 0.02) !important;
    }
</style>
@endpush
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Residents</h4>
        <p class="text-muted small mb-0">Manage all registered residents</p>
    </div>
    <a href="{{ route('residents.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Add Resident
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <a href="{{ route('residents.index') }}" class="text-decoration-none">
            <div class="stat-card clickable-card {{ !request()->hasAny(['sex', 'is_senior', 'is_pwd', 'search', 'purok_id', 'voter_status']) ? 'active-card' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($stats['total']) }}</div>
                        <div class="text-muted small">Total Residents</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg">
        <a href="{{ route('residents.index', ['sex' => 'male']) }}" class="text-decoration-none">
            <div class="stat-card clickable-card {{ request('sex') === 'male' ? 'active-card' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-gender-male"></i></div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($stats['male']) }}</div>
                        <div class="text-muted small">Male</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg">
        <a href="{{ route('residents.index', ['sex' => 'female']) }}" class="text-decoration-none">
            <div class="stat-card clickable-card {{ request('sex') === 'female' ? 'active-card' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-pink bg-opacity-10" style="background:#fce7f3"><i class="bi bi-gender-female" style="color:#9d174d"></i></div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($stats['female']) }}</div>
                        <div class="text-muted small">Female</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg">
        <a href="{{ route('residents.index', ['is_senior' => 1]) }}" class="text-decoration-none">
            <div class="stat-card clickable-card {{ request('is_senior') ? 'active-card' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-hearts"></i></div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($stats['seniors']) }}</div>
                        <div class="text-muted small">Senior Citizens</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg">
        <a href="{{ route('residents.index', ['is_pwd' => 1]) }}" class="text-decoration-none">
            <div class="stat-card clickable-card {{ request('is_pwd') ? 'active-card' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($stats['pwds']) }}</div>
                        <div class="text-muted small">Total PWDs</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="table-card mb-3">
    <div class="p-3 border-bottom bg-light">
        <form method="GET" id="filterForm" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm" placeholder="Search name, contact...">
            </div>
            <div class="col-md-2">
                <select name="purok_id" class="form-select form-select-sm">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ request('purok_id') == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sex" class="form-select form-select-sm">
                    <option value="">All Sexes</option>
                    <option value="male"   {{ request('sex') === 'male'   ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('sex') === 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="voter_status" class="form-select form-select-sm">
                    <option value="">All Voter Status</option>
                    <option value="registered" {{ request('voter_status') === 'registered' ? 'selected' : '' }}>Registered Voter</option>
                </select>
            </div>
            <div class="col-md-auto d-flex flex-column justify-content-end mb-1">
                <div class="d-flex gap-3">
                    <div class="form-check form-check-sm mb-0">
                        <input class="form-check-input" type="checkbox" name="is_senior" value="1"
                            id="chk_senior" {{ request('is_senior') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="chk_senior">Senior</label>
                    </div>
                    <div class="form-check form-check-sm mb-0">
                        <input class="form-check-input" type="checkbox" name="is_pwd" value="1"
                            id="chk_pwd" {{ request('is_pwd') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="chk_pwd">PWD</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2 d-flex justify-content-between">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </div>
            </div>
            <div class="col-12 mt-2 pt-2 border-top">
                <x-pagination-limit formId="filterForm" default="20" />
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Age / Sex</th>
                    <th>Purok</th>
                    <th>Household</th>
                    <th>Voter</th>
                    <th>Tags</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $resident)
                <tr>
                    <td class="text-muted small">{{ $resident->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $resident->profile_photo_url }}" class="avatar" alt="">
                            <div>
                                <a href="{{ route('residents.show', $resident) }}" class="fw-semibold text-decoration-none text-primary">
                                    {{ $resident->full_name }}
                                </a>
                                <div class="text-muted small mt-1">{{ $resident->contact_number ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $resident->sex === 'male' ? 'badge-male' : ($resident->sex === 'female' ? 'badge-female' : 'badge-other') }}">
                            {{ $resident->sex === 'other' ? 'Other' : ucfirst($resident->sex) }}
                        </span>
                        <span class="text-muted small ms-1">{{ $resident->age }} yrs</span>
                    </td>
                    <td class="small">
                        @if($resident->purok)
                            <a href="{{ route('puroks.show', $resident->purok) }}" class="text-decoration-none text-primary fw-semibold">
                                {{ $resident->purok->name }}
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="small">
                        @if($resident->household)
                            <a href="{{ route('households.show', $resident->household) }}" class="text-decoration-none text-primary fw-semibold">
                                {{ $resident->household->household_no }}
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($resident->voterInfo?->is_registered)
                            <span class="badge bg-success-subtle text-success">Registered</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        @if($resident->is_senior_citizen)
                            <span class="badge bg-warning-subtle text-warning">SC</span>
                        @endif
                        @if($resident->is_pwd)
                            <span class="badge bg-info-subtle text-info">PWD</span>
                        @endif
                        @if($resident->is_4ps_beneficiary)
                            <span class="badge bg-purple-subtle" style="background:#f3e8ff;color:#6b21a8">4Ps</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('residents.show', $resident) }}"
                                class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('residents.edit', $resident) }}"
                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                        No residents found.
                        <a href="{{ route('residents.create') }}">Add one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($residents->hasPages())
    <div class="p-3 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Showing {{ $residents->firstItem() }}–{{ $residents->lastItem() }} of {{ $residents->total() }}
        </div>
        {{ $residents->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection