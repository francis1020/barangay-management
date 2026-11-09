@extends('layouts.app')

@section('title', 'Edit Purok')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('puroks.index') }}" class="text-decoration-none text-muted">Puroks</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $purok->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit Purok: {{ $purok->name }}</h4>
        <p class="text-muted small mb-0">Update the sub-division information</p>
    </div>
    <x-back-button url="{{ route('puroks.index') }}" />
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('puroks.update', $purok) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    @include('puroks._form')

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('puroks.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Update Purok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
