@extends('layouts.main')

@section('title', 'Room Types - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Room Types</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Room Types</h1>
        <p class="page-subtitle">Manage different types of rooms and their configurations</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomTypeModal">
        <i class="bi bi-plus-circle me-2"></i>
        Add Room Type
    </button>
</div>

<!-- Room Types Grid -->
<div class="row g-4">
    @forelse($roomTypes as $roomType)
    <div class="col-xl-4 col-lg-6">
        <div class="card h-100 room-type-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="room-type-icon me-3">
                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-house-door fs-2 text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">{{ $roomType->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $roomType->rooms_count ?? 0 }} {{ Str::plural('Room', $roomType->rooms_count ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewRoomTypeModal{{ $roomType->id }}">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editRoomTypeModal{{ $roomType->id }}">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteRoomTypeModal{{ $roomType->id }}">
                                <i class="bi bi-trash me-2"></i>Delete
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="room-type-info mb-3">
                    @if($roomType->description)
                    <p class="text-muted mb-3">{{ Str::limit($roomType->description, 100) }}</p>
                    @endif
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people text-muted me-2"></i>
                                <span class="text-sm">{{ $roomType->max_occupancy ?? 0 }} Guests</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-currency-dollar text-muted me-2"></i>
                                <span class="text-sm">${{ number_format($roomType->price_per_night ?? 0, 2) }}/night</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-rulers text-muted me-2"></i>
                                <span class="text-sm">{{ $roomType->size ?? 'N/A' }} sqft</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bed text-muted me-2"></i>
                                <span class="text-sm">{{ $roomType->bed_type ?? 'Standard' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($roomType->amenities)
                    <div class="amenities mb-3">
                        <h6 class="text-sm fw-semibold mb-2">Amenities:</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @if($roomType->has_wifi)
                                <span class="badge bg-light text-dark">WiFi</span>
                            @endif
                            @if($roomType->has_tv)
                                <span class="badge bg-light text-dark">TV</span>
                            @endif
                            @if($roomType->has_ac)
                                <span class="badge bg-light text-dark">AC</span>
                            @endif
                            @if($roomType->has_breakfast)
                                <span class="badge bg-light text-dark">Breakfast</span>
                            @endif
                            @if($roomType->has_parking)
                                <span class="badge bg-light text-dark">Parking</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="room-type-stats">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            {{ $roomType->available_rooms ?? 0 }} Available
                        </span>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            {{ $roomType->occupied_rooms ?? 0 }} Occupied
                        </span>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoomTypeModal{{ $roomType->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoomTypeModal{{ $roomType->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Room Type Modals -->
    @include('room-types._modals', ['roomType' => $roomType])
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-grid-3x3-gap fs-1 text-muted d-block mb-3"></i>
                <h5>No room types found</h5>
                <p class="text-muted">Start by adding your first room type</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomTypeModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Room Type
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($roomTypes->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $roomTypes->links('pagination::bootstrap-5') }}
</div>
@endif

@include('room-types._modal_create')
@endsection

@push('styles')
<style>
.room-type-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.room-type-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.room-type-info .text-sm {
    font-size: 0.875rem;
}

.amenities .badge {
    font-size: 0.7rem;
}
</style>
@endpush
