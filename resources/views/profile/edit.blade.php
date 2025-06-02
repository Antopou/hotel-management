@extends('layouts.app')

@section('content')
<div class="container">
    <div class="profile-header bg-primary text-white p-4 rounded mb-4">
        <h2>{{ auth()->user()->name }}</h2>
        <p>{{ auth()->user()->email }}</p>
    </div>

    <!-- Update Profile -->
    <div class="card mb-4">
        <div class="card-header"><strong>Update Profile Information</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card mb-4">
        <div class="card-header"><strong>Change Password</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="card border-danger">
        <div class="card-header text-danger"><strong>Delete Account</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete My Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
