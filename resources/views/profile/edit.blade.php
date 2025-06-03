@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Back Button --}}
    <!-- @if(isset($backUrl))
        <a href="{{ $backUrl }}" class="btn btn-secondary mb-3">Back</a>
    @endif -->

    <div class="profile-header bg-primary text-white p-4 rounded mb-4 d-flex align-items-center">
        <div class="me-3">
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="rounded-circle" width="100" height="100">
            @else
                <img src="{{ asset('default-profile.png') }}" class="rounded-circle" width="100" height="100">
            @endif
        </div>
        <div>
            <h2>{{ auth()->user()->name }}</h2>
            <p>{{ auth()->user()->email }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Update Profile -->
    <div class="card mb-4">
        <div class="card-header"><strong>Update Profile Information</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                <div class="mb-3">
                    <label>Profile Image (Max 2MB, JPEG/PNG)</label>
                    <input type="file" name="profile_image" class="form-control">
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
