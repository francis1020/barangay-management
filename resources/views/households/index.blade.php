@extends('layouts.app')

@section('title', 'Households')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Households</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Households</h4>
        <p class="text-muted small mb-0">Manage all registered households and their members</p>
    </div>
    <a href="{{ route('households.create') }}" class="btn btn-primary">
        <i class="bi bi-house-add me-1"></i> Register Household
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-houses"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total']) }}</div>
                    <div class="text-muted small">Total Households</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-people"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['members']) }}</div>
                    <div class="text-muted small">Total Population</div>
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
                    <div class="text-muted small">Total Seniors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-person-badge"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['pwds']) }}</div>
                    <div class="text-muted small">Total PWDs</div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Filters --}}
<div class="table-card mb-3">
    <div class="p-3 border-bottom bg-light">
        <form method="GET" id="filterFormHousehold" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control form-control-sm" placeholder="Search household no, address...">
            </div>
            <div class="col-md-3">
                <select name="purok_id" class="form-select form-select-sm">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ request('purok_id') == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 d-flex justify-content-between">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('households.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                </div>
            </div>
            <div class="col-12 mt-2 pt-2 border-top">
                <x-pagination-limit formId="filterFormHousehold" default="20" />
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>HH No.</th>
                    <th>Head of Household</th>
                    <th>Purok</th>
                    <th>Members</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($households as $household)
                <tr>
                    <td class="fw-semibold">
                        <a href="{{ route('households.show', $household) }}" class="text-decoration-none text-primary fw-semibold">
                            {{ $household->household_no }}
                        </a>
                    </td>
                    <td>
                        @if($household->head)
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $household->head->profile_photo_url }}" class="avatar" alt="">
                                <div>
                                    <a href="{{ route('residents.show', $household->head) }}" class="fw-semibold text-decoration-none text-primary">{{ $household->head->full_name }}</a>
                                    <div class="text-muted small">{{ $household->head->contact_number ?? '—' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-danger italic small">No Head Assigned</span>
                        @endif
                    </td>
                    <td>
                        @if($household->purok)
                            <a href="{{ route('puroks.show', $household->purok) }}" class="text-decoration-none text-primary">{{ $household->purok->name }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ $household->residents->count() }} members
                        </span>
                    </td>
                    <td class="small">{{ Str::limit($household->address, 30) }}</td>
                    <td>
                        <span class="badge {{ $household->ownership_status === 'owned' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($household->ownership_status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('households.show', $household) }}"
                                class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('households.edit', $household) }}"
                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        No households found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($households->hasPages())
    <div class="p-3 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Showing {{ $households->firstItem() }}–{{ $households->lastItem() }} of {{ $households->total() }}
        </div>
        {{ $households->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
