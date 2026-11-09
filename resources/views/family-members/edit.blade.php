@extends('layouts.app')

@section('title', 'Edit Family Member')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('family-members.index') }}">Family Members</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit Member</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0 fw-bold">Edit Family Member</h4>
                    <p class="text-muted small mb-0">Update the relationship between two residents.</p>
                </div>
                <a href="{{ route('family-members.index') }}" class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('family-members.update', $familyMember->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label small fw-bold">Primary Resident</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $familyMember->resident->full_name }}" readonly>
                        </div>

                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label small fw-bold">Related Resident</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $familyMember->relatedResident->full_name }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Relationship <span class="text-danger">*</span></label>
                            <p class="small text-muted mb-2"><strong>{{ $familyMember->resident->first_name }}</strong> is
                                the <strong class="text-dark">X</strong> of
                                <strong>{{ $familyMember->relatedResident->first_name }}</strong>.
                            </p>
                            <select name="relationship" class="form-select shadow-none" required>
                                <option value="">Select Relationship</option>
                                @foreach(['spouse', 'child', 'father', 'mother', 'sibling', 'grandparent', 'grandchild', 'aunt', 'uncle', 'cousin', 'in-law', 'partner', 'other'] as $rel)
                                    <option value="{{ $rel }}" {{ $familyMember->relationship === $rel ? 'selected' : '' }}>
                                        {{ ucfirst($rel) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                            <a href="{{ route('family-members.index') }}" class="btn btn-light px-4 border">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check2 me-1"></i> Update Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection