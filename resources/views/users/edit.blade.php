@extends('layouts.app')

@section('title', 'Edit User')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none text-muted">Users</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Edit User: {{ $user->name }}</h4>
        <p class="text-muted small mb-0">Update user details and assigned roles</p>
    </div>
    <x-back-button url="{{ route('users.index') }}" />
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="table-card p-4">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control shadow-none @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Assign Role</label>
                        <select name="role_id" class="form-select shadow-none @error('role_id') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text small mt-1">Changing the role will affect the user's permissions immediately.</div>
                    </div>

                    <div class="col-md-12">
                        <hr class="my-2">
                        <div class="fw-bold small mb-1">Change Password</div>
                        <p class="text-muted extra-small mb-3">Leave these fields blank if you don't want to change the password.</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">New Password</label>
                        <input type="password" name="password" class="form-control shadow-none @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control shadow-none">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
