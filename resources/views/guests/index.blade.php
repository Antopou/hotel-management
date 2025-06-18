@extends('layouts.main')

@section('title', 'Guests - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Guests</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Guest Management</h1>
        <p class="page-subtitle">Manage your hotel guests and their information</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGuestModal">
        <i class="bi bi-person-plus me-2"></i>
        Add New Guest
    </button>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('guests.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Search by name, email, or phone">
            </div>
            <div class="col-md-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">All Genders</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Guests Grid -->
<div class="row g-4">
    @forelse($guests as $guest)
    <div class="col-xl-4 col-lg-6">
        <div class="card h-100 guest-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person fs-3 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">{{ $guest->name }}</h5>
                            <p class="text-muted mb-0 small">Guest ID: {{ $guest->guest_code }}</p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewGuestModal{{ $guest->id }}">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $guest->id }}">
                                <i class="bi bi-trash me-2"></i>Delete
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="guest-info">
                    @if($guest->email)
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-envelope text-muted me-2"></i>
                        <span class="text-sm">{{ $guest->email }}</span>
                    </div>
                    @endif
                    
                    @if($guest->tel)
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone text-muted me-2"></i>
                        <span class="text-sm">{{ $guest->tel }}</span>
                    </div>
                    @endif
                    
                    @if($guest->gender)
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-person-badge text-muted me-2"></i>
                        <span class="text-sm">{{ $guest->gender }}</span>
                    </div>
                    @endif
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-plus text-muted me-2"></i>
                        <span class="text-sm">Joined {{ $guest->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="guest-stats">
                        <span class="badge bg-info bg-opacity-10 text-info">
                            {{ $guest->checkins_count ?? 0 }} {{ Str::plural('Stay', $guest->checkins_count ?? 0) }}
                        </span>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewGuestModal{{ $guest->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Guest Modals -->
    @include('guests._modals', ['guest' => $guest])
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
                <h5>No guests found</h5>
                <p class="text-muted">Start by adding your first guest</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGuestModal">
                    <i class="bi bi-person-plus me-2"></i>Add New Guest
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($guests->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $guests->links('pagination::bootstrap-5') }}
</div>
@endif

@include('guests._modal_create')
@endsection

@push('styles')
<style>
.guest-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.guest-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.guest-info .text-sm {
    font-size: 0.875rem;
}
</style>
@endpush
