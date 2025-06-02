@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Room Management</h3>
        <a href="{{ route('rooms.create') }}" class="btn btn-success">Add New</a>
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Search by Room Name">
        </div>
        <div class="col-md-3">
            <select name="room_type_code" class="form-select">
                <option value="">All Room Types</option>
                @foreach ($roomTypes as $type)
                    <option value="{{ $type->room_type_code }}" {{ request('room_type_code') == $type->room_type_code ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                <option value="Occupied" {{ request('status') == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="Cleaning" {{ request('status') == 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    {{-- Card-style Room List --}}
    @forelse ($rooms as $room)
        @php
            $roomTypeImages = [
                'Single Room' => asset('images/room_types/single.jpg'),
                'Double Room' => asset('images/room_types/double.jpg'),
                'Standard Room' => asset('images/room_types/junior.jpg'),
                'Deluxe Room' => asset('images/room_types/deluxe.jpg'),
            ];
            $roomTypeName = $room->roomType->name ?? 'default';
            $imageUrl = $roomTypeImages[$roomTypeName] ?? asset('images/room_types/default.jpg');
        @endphp

        <div class="border rounded mb-3 p-3 shadow-sm bg-white card-hover">
            <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                <img src="{{ $imageUrl }}" alt="{{ $roomTypeName }}" class="rounded" style="width: 120px; height: 90px; object-fit: cover;">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1">{{ $room->name }}</h5>
                            <div class="text-muted small mb-1">
                                <strong>Room Type:</strong> {{ $roomTypeName }}
                            </div>
                            <div class="text-muted small">
                                <strong>Status:</strong>
                                <span class="fw-bold text-{{ 
                                    $room->status === 'Available' ? 'success' :
                                    ($room->status === 'Occupied' ? 'danger' :
                                    ($room->status === 'Cleaning' ? 'warning' : 'secondary'))
                                }}">
                                    {{ $room->status }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRoomModal{{ $room->id }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            {{-- View Modal --}}
            <div class="modal fade" id="viewRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="viewRoomLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog custom-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Room Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <img src="{{ $imageUrl }}" alt="{{ $roomTypeName }}" class="img-fluid rounded">
                                </div>
                                <div class="col-md-8">
                                    <dl class="row">
                                        <dt class="col-sm-4">Room Code</dt>
                                        <dd class="col-sm-8">{{ $room->room_code }}</dd>

                                        <dt class="col-sm-4">Name</dt>
                                        <dd class="col-sm-8">{{ $room->name }}</dd>

                                        <dt class="col-sm-4">Room Type</dt>
                                        <dd class="col-sm-8">{{ $roomTypeName }}</dd>

                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8">{{ $room->status }}</dd>

                                        <dt class="col-sm-4">Created At</dt>
                                        <dd class="col-sm-8">{{ $room->created_at->format('d M Y') }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomModalLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog custom-modal">
                    <div class="modal-content">
                        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Room</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Room Type</label>
                                    <select name="room_type_code" class="form-select" required>
                                        @foreach ($roomTypes as $type)
                                            <option value="{{ $type->room_type_code }}" {{ $room->room_type_code === $type->room_type_code ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <select name="status" id="status" class="form-select" required>
                                    <option value="Available" {{ $room->status === 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Occupied" {{ $room->status === 'Occupied' ? 'selected' : '' }}>Occupied</option>
                                    <option value="Cleaning" {{ $room->status === 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                                    <option value="Maintenance" {{ $room->status === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Room</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Delete Modal --}}
            <div class="modal fade" id="deleteRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="deleteRoomLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <img src="{{ $imageUrl }}" alt="{{ $roomTypeName }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                    <div>
                                        <p>Are you sure you want to delete the room <strong>{{ $room->name }}</strong>?</p>
                                        <p class="text-muted small mb-0">Type: {{ $roomTypeName }}</p>
                                    </div>
                                </div>
                                <p class="text-danger">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    @empty
        <div class="alert alert-info">No rooms available.</div>
    @endforelse

    {{-- Pagination --}}
    <div class="pt-3">
        {{ $rooms->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
