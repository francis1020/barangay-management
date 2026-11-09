@extends('layouts.app')

@section('title', 'Edit Resident')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}" class="text-decoration-none text-muted">Residents</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit: {{ $resident->full_name }}</h4>
        <p class="text-muted small mb-0">Update resident profile</p>
    </div>
    <x-back-button url="{{ route('residents.show', $resident) }}" />
</div>

<form method="POST" action="{{ route('residents.update', $resident) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')
    @include('residents._form', ['resident' => $resident])
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Save Changes
        </button>
        <a href="{{ route('residents.show', $resident) }}" class="btn btn-light">Cancel</a>
    </div>
</form>
@endsection