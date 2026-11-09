@extends('layouts.app')

@section('title', 'Household Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('households.index') }}"
            class="text-decoration-none text-muted">Households</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Details</li>
@endsection

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Household Details</h4>
            <p class="text-muted small mb-0">Household ID #{{ $household->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <x-back-button url="{{ route('households.index') }}" />
            <a href="{{ route('households.edit', $household) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <form action="{{ route('households.destroy', $household) }}" method="POST" class="swal-confirm"
                data-message="Are you sure you want to delete this household? This action cannot be undone.">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-gender-male"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['male']) }}</div>
                        <div class="text-muted small">Male in this Household</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-gender-female"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['female']) }}</div>
                        <div class="text-muted small">Female in this Household</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['pwds']) }}</div>
                        <div class="text-muted small">PWD in this Household</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-heart"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['seniors']) }}</div>
                        <div class="text-muted small">Senior in this Household</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profiling & Demographics Section --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2"></i>Household Profiling & Demographics</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Age Groups --}}
                        <div class="col-md-3">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3">Age Groups</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach($stats['profiling']['age_groups'] as $group => $count)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small">{{ $group }}</span>
                                        <span class="badge bg-light text-dark border">{{ $count }}</span>
                                    </div>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $stats['profiling']['total_members'] > 0 ? ($count / $stats['profiling']['total_members'] * 100) : 0 }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Employment Status --}}
                        <div class="col-md-3">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3">Employment Status</h6>
                            @forelse($stats['profiling']['employment'] as $status => $count)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small">{{ $status }}</span>
                                    <span class="fw-bold small">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="text-muted small">No employment data.</p>
                            @endforelse
                        </div>

                        {{-- Education Level --}}
                        <div class="col-md-3">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3">Education Level</h6>
                            @forelse($stats['profiling']['education'] as $level => $count)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="extra-small text-truncate" style="max-width: 150px;" title="{{ $level }}">{{ $level }}</span>
                                        <span class="fw-bold small">{{ $count }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small">No education data.</p>
                            @endforelse
                        </div>

                        {{-- Special Tags --}}
                        <div class="col-md-3">
                            <h6 class="small fw-bold text-muted text-uppercase mb-3">Special Designations</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="p-2 border rounded bg-light flex-fill">
                                    <div class="extra-small text-muted">PWD</div>
                                    <div class="fw-bold">{{ $stats['profiling']['special']['pwd'] }}</div>
                                </div>
                                <div class="p-2 border rounded bg-light flex-fill">
                                    <div class="extra-small text-muted">Senior</div>
                                    <div class="fw-bold">{{ $stats['profiling']['special']['senior'] }}</div>
                                </div>
                                <div class="p-2 border rounded bg-light flex-fill">
                                    <div class="extra-small text-muted">Solo Parent</div>
                                    <div class="fw-bold">{{ $stats['profiling']['special']['solo_parent'] }}</div>
                                </div>
                                <div class="p-2 border rounded bg-light flex-fill">
                                    <div class="extra-small text-muted">4Ps Beneficiary</div>
                                    <div class="fw-bold">{{ $stats['profiling']['special']['beneficiary_4ps'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Household Info Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 64px; height: 64px;">
                            <i class="bi bi-house-door fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-0">{{ $household->household_no }}</h5>
                        <p class="text-muted small">Household Number</p>
                    </div>

                    <div class="info-list">
                        <div class="mb-3">
                            <label class="text-muted small d-block">Purok</label>
                            <span class="fw-semibold">{{ $household->purok->name ?? '—' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Address</label>
                            <span class="fw-semibold">{{ $household->address }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Ownership Status</label>
                            <span
                                class="badge {{ $household->ownership_status === 'owned' ? 'badge-active' : 'badge-inactive' }}">
                                {{ ucfirst($household->ownership_status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Housing Type</label>
                            <span
                                class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $household->housing_type) }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Income Class</label>
                            <span class="fw-semibold text-capitalize">{{ $household->income_class ?? '—' }}</span>
                        </div>
                        <div>
                            <label class="text-muted small d-block">Approx. Monthly Income</label>
                            <span
                                class="fw-semibold text-primary">₱{{ number_format($household->monthly_income, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Members List --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-bold">Household Members ({{ $household->residents->count() }})</h6>
                    {{-- Add Member button could go here --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Role</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Voter Status</th>
                                <th class="pe-4 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($household->residents as $resident)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $resident->profile_photo_url }}" class="avatar" alt="">
                                            <div>
                                                <a href="{{ route('residents.show', $resident) }}"
                                                    class="fw-semibold text-decoration-none text-primary">
                                                    {{ $resident->full_name }}
                                                </a>
                                                <div class="text-muted extra-small mt-1">
                                                    {{ $resident->contact_number ?? 'No contact' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($household->head_resident_id === $resident->id)
                                            <span class="badge bg-primary-subtle text-primary">Head</span>
                                        @else
                                            <span class="text-muted small">Member</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($resident->sex) }}</td>
                                    <td>{{ $resident->age }} yrs</td>
                                    <td>
                                        @if($resident->voterInfo)
                                            <span class="badge badge-active">Registered</span>
                                        @else
                                            <span class="badge badge-inactive">Not Reg.</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('residents.show', $resident) }}"
                                            class="btn btn-sm btn-icon btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No members registered in this household.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection