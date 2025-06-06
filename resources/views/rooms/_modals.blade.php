{{-- resources/views/rooms/partials/_modals.blade.php --}}

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

{{-- Edit Room Modal --}}
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

{{-- Delete Room Modal --}}
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
