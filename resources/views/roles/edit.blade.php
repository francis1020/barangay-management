@extends('layouts.app')

@section('title', 'Edit Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none text-muted">Roles</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit Role: {{ $role->name }}</h4>
        <p class="text-muted small mb-0">Update role settings and permission access</p>
    </div>
    <x-back-button url="{{ route('roles.index') }}" />
</div>

<form action="{{ route('roles.update', $role) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="table-card p-4">
                <div class="mb-4">
                    <label class="form-label small fw-bold">Role Name</label>
                    <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required @if($role->slug === 'super_admin') readonly @endif>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($role->slug === 'super_admin')
                        <div class="form-text extra-small text-danger mt-1">Super Admin role name cannot be changed to maintain system integrity.</div>
                    @endif
                </div>

                <div class="">
                    <label class="form-label small fw-bold">Description</label>
                    <textarea name="description" class="form-control shadow-none @error('description') is-invalid @enderror" rows="4">{{ old('description', $role->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100 py-2">Save Changes</button>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="table-card overflow-hidden">
                <div class="p-4 border-bottom bg-light">
                    <h6 class="mb-0 fw-bold">Permission Matrix</h6>
                    <p class="text-muted extra-small mb-0">Modify access rights for each system module</p>
                </div>
                
                @if($role->slug === 'super_admin')
                <div class="p-5 text-center">
                    <i class="bi bi-shield-check text-success fs-1 mb-3 d-block"></i>
                    <h6 class="fw-bold">Super Admin Permissions Restricted</h6>
                    <p class="text-muted small">The Super Admin role inherently possesses all system permissions. Granular control is disabled for this role to prevent accidental lockout.</p>
                </div>
                @else
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
                                <div class="form-check p-3 border rounded hover:bg-light transition cursor-pointer @if(in_array($permission->id, $rolePermissions)) border-primary bg-primary-subtle @endif">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm{{ $permission->id }}" @if(in_array($permission->id, $rolePermissions)) checked @endif>
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
                @endif
            </div>
        </div>
    </div>
</form>
@endsection
