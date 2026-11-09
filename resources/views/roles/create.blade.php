@extends('layouts.app')

@section('title', 'Create New Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none text-muted">Roles</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Create</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Create New Role</h4>
        <p class="text-muted small mb-0">Define a new system role and assign initial permissions</p>
    </div>
    <x-back-button url="{{ route('roles.index') }}" />
</div>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="table-card p-4">
                <div class="mb-4">
                    <label class="form-label small fw-bold">Role Name</label>
                    <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Field Staff">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="">
                    <label class="form-label small fw-bold">Description</label>
                    <textarea name="description" class="form-control shadow-none @error('description') is-invalid @enderror" rows="4" placeholder="Describe the purpose of this role...">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 py-2">Create Role</button>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-card overflow-hidden">
                <div class="p-4 border-bottom bg-light">
                    <h6 class="mb-0 fw-bold">Permission Matrix</h6>
                    <p class="text-muted extra-small mb-0">Assign access rights for each system module</p>
                </div>
                
                <div class="p-4">
                    @foreach($permissionsByModule as $module => $permissions)
                    <div class="mb-4 last:mb-0">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-folder-fill text-primary"></i>
                            <span class="fw-bold text-dark">{{ $module }}</span>
                        </div>
                        <div class="row g-3">
                            @foreach($permissions as $permission)
                            <div class="col-md-3">
                                <div class="form-check p-3 border rounded hover:bg-light transition cursor-pointer">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm{{ $permission->id }}">
                                    <label class="form-check-label small d-block" for="perm{{ $permission->id }}">
                                        {{ Str::title(explode(' ', $permission->name)[0]) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
