@extends('layouts.app')

@section('title', 'Edit Household')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('households.index') }}" class="text-decoration-none text-muted">Households</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit Household</h4>
        <p class="text-muted small mb-0">Household ID #{{ $household->id }} - {{ $household->household_no }}</p>
    </div>
    <div class="d-flex gap-2">
        <x-back-button url="{{ route('households.show', $household) }}" />
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Household Information</h5>
                <p class="text-muted small mb-0">Update household information and head of household.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('households.update', $household) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    @include('households._form')

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('households.show', $household) }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Update Household
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
