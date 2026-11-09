@extends('layouts.app')

@section('title', 'Role Management')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Roles</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Role Management</h4>
        <p class="text-muted small mb-0">Define and manage system roles and their permissions</p>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-shield-plus me-1"></i> Create New Role
    </a>
</div>

<div class="table-card">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
        <x-pagination-limit default="10" />
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Role Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Users Count</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold">{{ $role->name }}</div>
                    </td>
                    <td>
                        <code class="extra-small px-2 py-1 bg-light rounded text-dark">{{ $role->slug }}</code>
                    </td>
                    <td class="small text-muted">{{ Str::limit($role->description, 50) }}</td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary small">
                            {{ $role->users_count }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($role->slug !== 'super_admin')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="swal-confirm" data-message="WARNING: Deleting this role will also DELETE ALL USERS associated with it! Are you sure?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($roles->hasPages())
    <div class="p-3 border-top">
        {{ $roles->links() }}
    </div>
    @endif
</div>

<div class="mt-4 p-4 rounded bg-warning-subtle border border-warning-subtle">
    <div class="d-flex gap-3">
        <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
        <div>
            <div class="fw-bold text-warning-emphasis">Caution: Cascade Deletion</div>
            <p class="small text-warning-emphasis mb-0">Role deletion is final. All staff members currently assigned to a role will be permanently removed if that role is deleted. Always reassign users before deleting a role if you wish to keep them.</p>
        </div>
    </div>
</div>
@endsection
