@extends('layouts.main')

@section('title', 'Profile Settings - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile Settings</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile Settings</h1>
    <p class="page-subtitle">Manage your account information and preferences</p>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                    <i class="bi bi-person fs-1 text-primary"></i>
                </div>
                <h5 class="card-title">{{ Auth::user()->name }}</h5>
                <p class="text-muted">{{ Auth::user()->email }}</p>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-success">Active</span>
                    <span class="badge bg-info">Administrator</span>
                </div>
            </div>
        </div>

        <!-- Account Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Account Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Member Since</span>
                    <span class="fw-semibold">{{ Auth::user()->created_at->format('M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Last Login</span>
                    <span class="fw-semibold">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, H:i') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Profile Completion</span>
                    <span class="fw-semibold">85%</span>
                </div>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Profile Information</h6>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Update Password</h6>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card border-danger">
            <div class="card-header bg-danger bg-opacity-10">
                <h6 class="card-title mb-0 text-danger">Danger Zone</h6>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-xl {
    width: 80px;
    height: 80px;
}
</style>
@endpush
