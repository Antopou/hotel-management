{{-- resources/views/rooms/_modal_edit.blade.php --}}
<div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editRoomLabel{{ $room->id }}">
                        <i class="bi bi-door-closed"></i> Edit Room
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRoomName{{ $room->id }}" class="form-label">Room Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editRoomName{{ $room->id }}" class="form-control" value="{{ $room->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomType{{ $room->id }}" class="form-label">Room Type <span class="text-danger">*</span></label>
                        <select name="room_type_id" id="editRoomType{{ $room->id }}" class="form-select" required>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ $room->room_type_id == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomStatus{{ $room->id }}" class="form-label">Status</label>
                        <select name="status" id="editRoomStatus{{ $room->id }}" class="form-select">
                            <option value="Available" {{ $room->status == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Occupied" {{ $room->status == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="Cleaning" {{ $room->status == 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                            <option value="Maintenance" {{ $room->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomDescription{{ $room->id }}" class="form-label">Description</label>
                        <textarea name="description" id="editRoomDescription{{ $room->id }}" class="form-control" rows="3">{{ $room->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
