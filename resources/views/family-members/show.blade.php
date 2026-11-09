@extends('layouts.app')

@section('title', "$resident->full_name's Family")

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}"
            class="text-decoration-none text-muted">Residents</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('family-members.index') }}" class="text-decoration-none text-muted">
            Family Members</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $resident->full_name }}</li>
@endsection

@section('content')
    <div class="row">
        {{-- Resident Summary Card --}}
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                @if($resident->photo)
                    <img src="{{ asset('storage/' . $resident->photo) }}" class="rounded-circle mx-auto mb-3 border" width="90"
                        height="90" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                        style="width:90px;height:90px;font-size:1.6rem;">
                        {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $resident->full_name }}</h5>
                <p class="text-muted small mb-3">{{ $resident->purok?->name ? 'Purok ' . $resident->purok->name : '' }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('residents.show', $resident) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person me-1"></i> View Profile
                    </a>
                    <a href="{{ route('family-members.create-for', $resident->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus me-1"></i> Add Member
                    </a>
                </div>
            </div>
        </div>

        {{-- Family List --}}
        <div class="col-md-8 col-lg-9">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold mb-0">
                    Family Members <span class="badge bg-primary ms-1">{{ $familyMembers->count() }}</span>
                </h5>
                <a href="{{ route('family-members.index') }}" class="btn btn-light border btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            @if($familyMembers->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                        No Family Members recorded yet.
                        <div class="mt-3">
                            <a href="{{ route('family-members.create-for', $resident->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add First Member
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Related Resident</th>
                                    <th>Relationship</th>
                                    <th>Age</th>
                                    <th>Purok</th>
                                    <th class="pe-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($familyMembers as $member)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($member->relatedResident->photo)
                                                    <img src="{{ asset('storage/' . $member->relatedResident->photo) }}"
                                                        class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                                        style="width:32px;height:32px;font-size:.75rem;">
                                                        {{ strtoupper(substr($member->relatedResident->first_name, 0, 1)) }}{{ strtoupper(substr($member->relatedResident->last_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('residents.show', $member->relatedResident) }}"
                                                    class="text-decoration-none fw-semibold text-primary">
                                                    {{ $member->relatedResident->full_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            @if($member->relationship)
                                                <span
                                                    class="badge bg-light text-dark border">{{ ucfirst($member->relationship) }}</span>
                                            @else
                                                <span class="badge bg-secondary text-white">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $member->relatedResident->age ? $member->relatedResident->age . ' yrs' : '—' }}
                                        </td>
                                        <td class="small text-muted">{{ $member->relatedResident->purok?->name ?? '—' }}</td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('family-members.edit', $member->id) }}"
                                                    class="btn btn-sm btn-outline-secondary" title="Edit Relationship">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('family-members.destroy', $member->id) }}" method="POST"
                                                    class="d-inline swal-confirm" data-message="Remove this family connection?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection