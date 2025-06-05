@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Room Management</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRoomModal">Add New</button>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    {{-- Room List --}}
    @forelse ($rooms as $room)
        @php
            $roomType = $room->roomType;
            $roomTypeName = $roomType->name ?? 'N/A';
            $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
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
                                    $room->status === 'available' ? 'success' :
                                    ($room->status === 'occupied' ? 'danger' :
                                    ($room->status === 'cleaning' ? 'warning' : 'secondary'))
                                }}">{{ $room->status }}</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            {{-- View Button (Still per-room as it might fetch specific data) --}}
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomModal{{ $room->id }}">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            {{-- Edit Button (Uses single modal, passes data via data attributes) --}}
                            <button class="btn btn-secondary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRoomModal"
                                    data-room-id="{{ $room->id }}"
                                    data-room-name="{{ $room->name }}"
                                    data-room-type-code="{{ $room->room_type_code }}"
                                    data-room-status="{{ $room->status }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            {{-- Delete Button (Uses single modal, passes data via data attributes) --}}
                            <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteRoomModal"
                                    data-room-id="{{ $room->id }}"
                                    data-room-name="{{ $room->name }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- View Modal (Still per-room as it's a simple display, no form submission) --}}
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
                                    <dd class="col-sm-8">
                                        <span class="fw-bold text-{{
                                            $room->status === 'Available' ? 'success' :
                                            ($room->status === 'Occupied' ? 'danger' :
                                            ($room->status === 'Cleaning' ? 'warning' : 'secondary'))
                                        }}">{{ $room->status }}</span>
                                    </dd>
                                    <dt class="col-sm-4">Created By</dt>
                                    <dd class="col-sm-8">{{ $room->created_by }}</dd> {{-- Replace with User Name if you have a relationship --}}
                                    <dt class="col-sm-4">Created At</dt>
                                    <dd class="col-sm-8">{{ $room->created_at->format('M d, Y H:i A') }}</dd>
                                    @if ($room->modified_by)
                                        <dt class="col-sm-4">Modified By</dt>
                                        <dd class="col-sm-8">{{ $room->modified_by }}</dd> {{-- Replace with User Name if you have a relationship --}}
                                        <dt class="col-sm-4">Last Modified</dt>
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
        <div class="alert alert-info">No rooms available. Try adjusting your filters or add a new room.</div>
    @endforelse

    {{-- Pagination --}}
    <div class="pt-3">
        {{ $rooms->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Create Room Modal --}}
<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Room Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter room name" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="room_type_code" class="form-label">Room Type</label>
                        <select name="room_type_code" class="form-select @error('room_type_code') is-invalid @enderror" required>
                            <option value="">Select a room type</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->room_type_code }}" {{ old('room_type_code') == $type->room_type_code ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select status</option>
                            @foreach (['Available', 'Occupied', 'Cleaning', 'Maintenance'] as $status)
                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Single Edit Room Modal --}}
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form id="editRoomForm" method="POST">
                @csrf
                @method('PUT') {{-- Or PATCH --}}
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="room_id" id="editRoomId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Room Name</label>
                        <input type="text" name="name" id="editName" class="form-control" placeholder="Enter room name" required>
                        {{-- Error display for AJAX validation (requires more JS for non-page reload forms) --}}
                        <small class="text-danger" id="editNameError"></small>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomTypeCode" class="form-label">Room Type</label>
                        <select name="room_type_code" id="editRoomTypeCode" class="form-select" required>
                            <option value="">Select a room type</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->room_type_code }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger" id="editRoomTypeCodeError"></small>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="">Select status</option>
                            @foreach (['Available', 'Occupied', 'Cleaning', 'Maintenance'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger" id="editStatusError"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Single Delete Room Modal --}}
<div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteRoomForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete room "<strong id="deleteRoomName"></strong>"? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
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

                modalForm.action = `/rooms/${roomId}`;
                modalIdInput.value = roomId;
                modalNameInput.value = roomName;
                modalRoomTypeCodeSelect.value = roomTypeCode;
                modalStatusSelect.value = roomStatus;

                document.getElementById('editNameError').innerText = '';
                document.getElementById('editRoomTypeCodeError').innerText = '';
                document.getElementById('editStatusError').innerText = '';
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

                modalForm.action = `/rooms/${roomId}`;
                modalRoomNameStrong.innerText = roomName;
            });
        }
    });
</script>

@if ($errors->any() && !session('edit_modal_errors'))
<script>
    var createModal = new bootstrap.Modal(document.getElementById('createRoomModal'));
    createModal.show();
</script>
@endif

@if ($errors->any() && session('edit_modal_errors'))
<script>
    var editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
    editModal.show();
</script>
@endif
@endsection
