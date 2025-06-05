@extends('layouts.main')

@section('content')
@include('partials.loader') {{-- Ensure your loader partial is correctly implemented if needed --}}

<div class="container-fluid py-4"> {{-- Changed from px-3 py-4 to container-fluid py-4 for consistency with main layout --}}
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Room Management</h3>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createRoomModal">
            <i class="bi bi-plus-circle me-2"></i> Add New Room
        </button>
    </div>

    {{-- Filter/Search Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3">Filter Rooms</h5>
            <form method="GET" action="{{ route('rooms.index') }}" class="row g-3">
                <div class="col-md-4 col-lg-3">
                    <label for="searchName" class="form-label visually-hidden">Room Name</label>
                    <input type="text" name="name" id="searchName" value="{{ request('name') }}" class="form-control" placeholder="Search by Room Name">
                </div>
                <div class="col-md-4 col-lg-3">
                    <label for="roomTypeFilter" class="form-label visually-hidden">Room Type</label>
                    <select name="room_type_code" id="roomTypeFilter" class="form-select">
                        <option value="">All Room Types</option>
                        @foreach ($roomTypes as $type)
                            <option value="{{ $type->room_type_code }}" {{ request('room_type_code') == $type->room_type_code ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-3">
                    <label for="statusFilter" class="form-label visually-hidden">Status</label>
                    <select name="status" id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Occupied" {{ request('status') == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="Cleaning" {{ request('status') == 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                        <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="col-12 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </button>
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary flex-grow-1">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Room List (Grid View) --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
        @forelse ($rooms as $room)
            @php
                $roomType = $room->roomType;
                $roomTypeName = $roomType->name ?? 'N/A';
                $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
            @endphp
            <div class="col">
                <div class="card h-100 shadow-sm border-0 card-hover">
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $roomTypeName }}" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary mb-1">{{ $room->name }}</h5>
                        <p class="card-text text-muted small mb-1">
                            <strong>Room Type:</strong> {{ $roomTypeName }}
                        </p>
                        <p class="card-text small">
                            <strong>Status:</strong>
                            <span class="badge bg-{{
                                $room->status === 'Available' ? 'success' :
                                ($room->status === 'Occupied' ? 'danger' :
                                ($room->status === 'Cleaning' ? 'warning' : 'secondary'))
                            }} text-uppercase">{{ $room->status }}</span>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3 d-flex justify-content-center gap-2">
                        {{-- View Button --}}
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}">
                            <i class="bi bi-eye-fill"></i> View
                        </button>
                        {{-- Edit Button --}}
                        <button class="btn btn-outline-secondary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editRoomModal"
                                data-room-id="{{ $room->id }}"
                                data-room-name="{{ $room->name }}"
                                data-room-type-code="{{ $room->room_type_code }}"
                                data-room-status="{{ $room->status }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        {{-- Delete Button --}}
                        <button class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteRoomModal"
                                data-room-id="{{ $room->id }}"
                                data-room-name="{{ $room->name }}">
                            <i class="bi bi-trash-fill"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

            {{-- View Modal (Still per-room for simplicity in fetching dynamic data) --}}
            <div class="modal fade" id="viewRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="viewRoomLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog custom-modal">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="viewRoomLabel{{ $room->id }}">Room Details - {{ $room->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <img src="{{ $imageUrl }}" alt="{{ $roomTypeName }}" class="img-fluid rounded shadow-sm">
                                </div>
                                <div class="col-md-7">
                                    <h4 class="mb-3">{{ $room->name }} <small class="text-muted fs-6">({{ $room->room_code }})</small></h4>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 text-muted">Room Type:</dt>
                                        <dd class="col-sm-8 fw-bold">{{ $roomTypeName }}</dd>

                                        <dt class="col-sm-4 text-muted">Current Status:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge bg-{{
                                                $room->status === 'Available' ? 'success' :
                                                ($room->status === 'Occupied' ? 'danger' :
                                                ($room->status === 'Cleaning' ? 'warning' : 'secondary'))
                                            }} text-uppercase">{{ $room->status }}</span>
                                        </dd>

                                        <dt class="col-sm-4 text-muted">Created By:</dt>
                                        <dd class="col-sm-8">{{ $room->created_by ?? 'N/A' }}</dd> {{-- Replace with User Name if you have a relationship --}}

                                        <dt class="col-sm-4 text-muted">Created At:</dt>
                                        <dd class="col-sm-8">{{ $room->created_at->format('M d, Y H:i A') }}</dd>

                                        @if ($room->modified_by)
                                            <dt class="col-sm-4 text-muted">Modified By:</dt>
                                            <dd class="col-sm-8">{{ $room->modified_by ?? 'N/A' }}</dd> {{-- Replace with User Name if you have a relationship --}}
                                            <dt class="col-sm-4 text-muted">Last Modified:</dt>
                                            <dd class="col-sm-8">{{ $room->updated_at->format('M d, Y H:i A') }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i> No rooms available. Try adjusting your filters or add a new room.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($rooms->hasPages())
        <div class="d-flex justify-content-center">
            {{ $rooms->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Create Room Modal --}}
<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createRoomLabel">Add New Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Room 101" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="room_type_code" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="room_type_code" id="room_type_code" class="form-select @error('room_type_code') is-invalid @enderror" required>
                            <option value="">Select a room type</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->room_type_code }}" {{ old('room_type_code') == $type->room_type_code ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select status</option>
                            @foreach (['Available', 'Occupied', 'Cleaning', 'Maintenance'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i> Save Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Single Edit Room Modal --}}
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editRoomForm" method="POST">
                @csrf
                @method('PUT') {{-- Or PATCH --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoomLabel">Edit Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="room_id" id="editRoomId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" placeholder="Enter room name" required>
                        <div class="text-danger mt-1" id="editNameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomTypeCode" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="room_type_code" id="editRoomTypeCode" class="form-select" required>
                            <option value="">Select a room type</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->room_type_code }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger mt-1" id="editRoomTypeCodeError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="">Select status</option>
                            @foreach (['Available', 'Occupied', 'Cleaning', 'Maintenance'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger mt-1" id="editStatusError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-2"></i> Update Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Single Delete Room Modal --}}
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="deleteRoomForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoomLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete room <strong id="deleteRoomName"></strong>?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Room Modal Logic
        var editRoomModal = document.getElementById('editRoomModal');
        if (editRoomModal) {
            editRoomModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var roomId = button.getAttribute('data-room-id');
                var roomName = button.getAttribute('data-room-name');
                var roomTypeCode = button.getAttribute('data-room-type-code');
                var roomStatus = button.getAttribute('data-room-status');

                var modalForm = editRoomModal.querySelector('#editRoomForm');
                var modalIdInput = editRoomModal.querySelector('#editRoomId');
                var modalNameInput = editRoomModal.querySelector('#editName');
                var modalRoomTypeCodeSelect = editRoomModal.querySelector('#editRoomTypeCode');
                var modalStatusSelect = editRoomModal.querySelector('#editStatus');

                modalForm.action = `/rooms/${roomId}`; // Ensure this matches your Laravel route for update
                modalIdInput.value = roomId;
                modalNameInput.value = roomName;
                modalRoomTypeCodeSelect.value = roomTypeCode;
                modalStatusSelect.value = roomStatus;

                // Clear previous errors (if any)
                document.getElementById('editNameError').innerText = '';
                document.getElementById('editRoomTypeCodeError').innerText = '';
                document.getElementById('editStatusError').innerText = '';

                // Remove is-invalid class from inputs
                modalNameInput.classList.remove('is-invalid');
                modalRoomTypeCodeSelect.classList.remove('is-invalid');
                modalStatusSelect.classList.remove('is-invalid');
            });
        }

        // Delete Room Modal Logic
        var deleteRoomModal = document.getElementById('deleteRoomModal');
        if (deleteRoomModal) {
            deleteRoomModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var roomId = button.getAttribute('data-room-id');
                var roomName = button.getAttribute('data-room-name');

                var modalForm = deleteRoomModal.querySelector('#deleteRoomForm');
                var modalRoomNameStrong = deleteRoomModal.querySelector('#deleteRoomName');

                modalForm.action = `/rooms/${roomId}`; // Ensure this matches your Laravel route for delete
                modalRoomNameStrong.innerText = roomName;
            });
        }
    });
</script>

@if ($errors->any())
    @if (old('name') || old('room_type_code') || old('status') || session('create_modal_errors'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var createModal = new bootstrap.Modal(document.getElementById('createRoomModal'));
                createModal.show();
            });
        </script>
    @elseif (session('edit_modal_errors'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
                editModal.show();
            });
        </script>
    @endif
@endif
@endsection
