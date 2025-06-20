@extends('layouts.main')

@section('title', 'Profile Settings - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Profile Settings</li>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Profile Settings</h1>
    <p class="page-subtitle text-muted">Manage your account information and preferences</p>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <!-- Profile Card -->
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ Auth::user()->profile_image_url }}"
                             alt="Profile Photo"
                             class="rounded-circle border border-3 border-primary shadow"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <label for="profile_photo" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1" style="cursor:pointer;">
                            <i class="bi bi-camera"></i>
                            <input type="file" id="profile_photo" name="profile_photo" class="d-none" onchange="this.form.submit()">
                        </label>
                    </div>
                </form>
                <h5 class="card-title mt-2 mb-0">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                <span class="badge bg-success">Active</span>
                @if(Auth::user()->is_admin ?? false)
                    <span class="badge bg-info">Administrator</span>
                @endif
            </div>
        </div>

        <!-- Account Stats -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">Account Statistics</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Member Since</span>
                    <span class="fw-semibold">{{ Auth::user()->created_at->format('M Y') }}</span>
                </div>
                {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Last Login</span>
                    <span class="fw-semibold">{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, H:i') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Profile Completion</span>
                    <span class="fw-semibold">85%</span>
                </div>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Profile Information -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">Profile Information</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch') <!-- Change this from 'put' to 'patch' -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">Update Password</h6>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input name="current_password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input name="password_confirmation" type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Password</button>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger bg-opacity-10">
                <h6 class="card-title mb-0 text-danger">Danger Zone</h6>
            </div>
            <div class="card-body">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                    <p class="mb-3">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data or information that you wish to retain before deleting your account.
                    </p>
                    @if(Auth::id() == 1)
                        <button type="button" class="btn btn-danger" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="The main admin account cannot be deleted for security reasons.">
                            <i class="bi bi-shield-lock me-1"></i>
                            Delete Account (Protected Admin)
                        </button>
                        <div class="alert alert-warning mt-3 mb-0 py-2 px-3" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            This admin account is protected and cannot be deleted.
                        </div>
                    @else
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Delete Account
                        </button>
                    @endif
                </form>
            </div>
        </div>

        {{-- Register New Admin Button --}}
        @if(Auth::user()->id == 1)
            <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#registerAdminModal">
                Register New Admin
            </button>

            <!-- Register Admin Modal -->
            <div class="modal fade" id="registerAdminModal" tabindex="-1" aria-labelledby="registerAdminModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post" action="{{ route('profile.registerAdmin') }}" id="registerAdminForm">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="registerAdminModalLabel">Register New Admin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input name="password" type="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="password_confirmation" type="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Register Admin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if(session('new_admin_id') && session('new_admin_email'))
    <div class="modal fade" id="switchUserModal" tabindex="-1" aria-labelledby="switchUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.switchUser') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ session('new_admin_id') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="switchUserModalLabel">Switch User?</h5>
                    </div>
                    <div class="modal-body">
                        <p>New admin <strong>{{ session('new_admin_name') }}</strong> registered.<br>
                        Do you want to switch to this account now?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Yes, Switch</button>
                        <button type="button" class="btn btn-secondary" onclick="closeSwitchUserModal()">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var switchUserModal = new bootstrap.Modal(document.getElementById('switchUserModal'));
            switchUserModal.show();
        });
    </script>
@endif

<!-- Delete Account Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you want to delete your account? This action cannot be undone.<br>
                        All your data will be permanently removed.
                    </p>
                    @if($errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Enter your password to confirm:</label>
                        <input type="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            deleteModal.show();
        });
    </script>
@endif
@endsection

@push('styles')
<style>
.avatar-xl {
    width: 100px;
    height: 100px;
}
</style>
@endpush

<script>
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
