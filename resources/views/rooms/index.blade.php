@extends('layouts.main')

@section('title', 'Rooms - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Rooms</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Room Management</h1>
        <p class="page-subtitle">Manage hotel rooms and their availability</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">
        <i class="bi bi-plus-circle me-2"></i>
        Add New Room
    </button>
</div>

<!-- Room Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total Rooms</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $rooms->total() }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-door-open fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Available</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $availableRooms ?? 0 }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Occupied</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $occupiedRooms ?? 0 }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-person-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Maintenance</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $maintenanceRooms ?? 0 }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-tools fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('rooms.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="form-control" placeholder="Search by room name or number">
            </div>
            <div class="col-md-3">
                <label for="room_type" class="form-label">Room Type</label>
                <select name="room_type" id="room_type" class="form-select">
                    <option value="">All Types</option>
                    @foreach($roomTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Rooms Grid -->
<div class="row g-4">
    @forelse($rooms as $room)
    <div class="col-xl-4 col-lg-6">
        <div class="card h-100 room-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="room-icon me-3">
                            @php
                                $statusColor = match($room->status ?? 'available') {
                                    'occupied' => 'danger',
                                    'maintenance' => 'warning',
                                    default => 'success'
                                };
                            @endphp
                            <div class="avatar-lg bg-{{ $statusColor }} bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-door-open fs-2 text-{{ $statusColor }}"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">{{ $room->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $room->roomType->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                                <i class="bi bi-pencil me-2"></i>Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteRoomModal{{ $room->id }}">
                                <i class="bi bi-trash me-2"></i>Delete
                            </a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="room-info mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people text-muted me-2"></i>
                                <span class="text-sm">{{ $room->capacity ?? 0 }} Guests</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-currency-dollar text-muted me-2"></i>
                                <span class="text-sm">${{ number_format($room->price ?? 0, 2) }}/night</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-wifi text-muted me-2"></i>
                                <span class="text-sm">Free WiFi</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-tv text-muted me-2"></i>
                                <span class="text-sm">Smart TV</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-{{ $statusColor }}">
                        {{ ucfirst($room->status ?? 'available') }}
                    </span>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Room Modals -->
    @include('rooms._modals', ['room' => $room])
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-door-open fs-1 text-muted d-block mb-3"></i>
                <h5>No rooms found</h5>
                <p class="text-muted">Start by adding your first room</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">
                    <i class="bi bi-plus-circle me-2"></i>Add New Room
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($rooms->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $rooms->links('pagination::bootstrap-5') }}
</div>
@endif

@include('rooms._modal_create')
@endsection

@push('styles')
<style>
.room-card {
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.room-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.avatar-lg {
    width: 60px;
    height: 60px;
}

.room-info .text-sm {
    font-size: 0.875rem;
}
</style>
@endpush
