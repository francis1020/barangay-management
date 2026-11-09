@extends('layouts.app')

@section('title', 'Family Members')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}"
            class="text-decoration-none text-muted">Residents</a>
    </li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Family Members</li>
@endsection

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Family Members</h4>
            <p class="text-muted small mb-0">Residents with recorded family relationships</p>
        </div>
        <a href="{{ route('family-members.create') }}" class="btn btn-primary">
            <i class="bi bi-diagram-3 me-1"></i> Add Member
        </a>
    </div>

    <div class="table-card mb-4">
        <div class="p-3 border-bottom bg-light">
            <form method="GET" id="filterForm" action="{{ route('family-members.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search by Resident Name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
                    @if(request('search'))
                        <a href="{{ route('family-members.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                    @endif
                </div>
                <div class="col-12 mt-2 pt-2 border-top">
                    <x-pagination-limit formId="filterForm" default="20" />
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Resident</th>
                        <th>Purok</th>
                        <th>Family Members</th>
                        <th class="pe-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($resident->photo)
                                        <img src="{{ asset('storage/' . $resident->photo) }}" class="rounded-circle" width="36"
                                            height="36" style="object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                            style="width:36px;height:36px;font-size:.85rem;">
                                            {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $resident->full_name }}</div>
                                        <div class="text-muted small">{{ $resident->age ? $resident->age . ' yrs' : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted">{{ $resident->purok?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $resident->family_members_count }}
                                    connection{{ $resident->family_members_count !== 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('family-members.show', $resident) }}"
                                        class="btn btn-sm btn-outline-secondary" title="View Family">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <a href="{{ route('family-members.create-for', $resident->id) }}"
                                        class="btn btn-sm btn-primary" title="Add Member">
                                        <i class="bi bi-plus-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-muted small">No residents with Family Members found.
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