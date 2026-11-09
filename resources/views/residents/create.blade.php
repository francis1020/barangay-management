@extends('layouts.app')

@section('title', 'Add Resident')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}" class="text-decoration-none text-muted">Residents</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Add New</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Register New Resident</h4>
        <p class="text-muted small mb-0">Fill in the resident's complete profile</p>
    </div>
    <x-back-button url="{{ route('residents.index') }}" />
</div>

<form method="POST" action="{{ route('residents.store') }}" enctype="multipart/form-data">
    @csrf
    @include('residents._form', ['resident' => null])
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-person-plus me-1"></i> Register Resident
        </button>
        <a href="{{ route('residents.index') }}" class="btn btn-light">Cancel</a>
    </div>
</form>
@endsection