{{-- resources/views/rooms/_modal_create.blade.php --}}
<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"><!-- Changed from modal-dialog to modal-dialog modal-lg -->
        <div class="modal-content">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createRoomLabel">
                        <i class="bi bi-door-open"></i> Add New Room
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roomName" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="roomName" class="form-control" required placeholder="e.g., Room 101">
                    </div>
                    <div class="mb-3">
                        <label for="roomType" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="room_type_id" id="roomType" class="form-select" required>
                            <option value="">-- Select Room Type --</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="roomStatus" class="form-label">Status</label>
                        <select name="status" id="roomStatus" class="form-select">
                            <option value="Available" selected>Available</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Cleaning">Cleaning</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="roomDescription" class="form-label">Description</label>
                        <textarea name="description" id="roomDescription" class="form-control" rows="3" placeholder="Optional room description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i> Create Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
