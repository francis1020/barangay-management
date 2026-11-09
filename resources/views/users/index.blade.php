@extends('layouts.app')

@section('title', 'User Management')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Users</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">User Management</h4>
        <p class="text-muted small mb-0">Manage system users and their roles</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i> Add New User
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
                    <th class="ps-4">Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar bg-primary-subtle text-primary">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-secondary-subtle text-secondary extra-small">It's You</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="small text-muted">{{ $user->email }}</td>
                    <td>
                        <span class="badge @if($user->isSuperAdmin()) bg-danger-subtle text-danger @else bg-info-subtle text-info @endif small">
                            {{ $user->role_label }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="swal-confirm" data-message="Are you sure you want to delete this user?">
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
    @if($users->hasPages())
    <div class="p-4 border-top">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
