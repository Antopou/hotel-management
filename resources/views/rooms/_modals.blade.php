{{-- rooms/_modals.blade.php --}}
{{-- $room is expected --}}

{{-- View Room Modal --}}
<div class="modal fade" id="viewRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="viewRoomLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewRoomLabel{{ $room->id }}">Room Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Room Code</dt>
                            <dd class="col-sm-7">{{ $room->room_code }}</dd>
                            <dt class="col-sm-5">Room Name</dt>
                            <dd class="col-sm-7">{{ $room->name }}</dd>
                            <dt class="col-sm-5">Room Type</dt>
                            <dd class="col-sm-7">{{ $room->roomType->name ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">Floor</dt>
                            <dd class="col-sm-7">
                                @php
                                    // Extract floor from room name (e.g., "Room 203" => 2)
                                    preg_match('/\d+/', $room->name, $matches);
                                    $floor = isset($matches[0]) ? substr($matches[0], 0, 1) : 'N/A';
                                @endphp
                                {{ $floor }}
                            </dd>
                            <dt class="col-sm-5">Price per Night</dt>
                            <dd class="col-sm-7">
                                ${{ number_format($room->roomType->price_per_night ?? 0, 2) }}
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Status</dt>
                            <dd class="col-sm-7">
                                @php
                                    $statusClass = match($room->status ?? 'available') {
                                        'occupied' => 'danger',
                                        'maintenance' => 'warning',
                                        default => 'success'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($room->status ?? 'available') }}</span>
                            </dd>
                            <dt class="col-sm-5">Size</dt>
                            <dd class="col-sm-7">
                                {{ $room->roomType->size ? $room->roomType->size . ' sqft' : 'N/A' }}
                            </dd>
                            <dt class="col-sm-5">Bed Type</dt>
                            <dd class="col-sm-7">
                                {{ $room->roomType->bed_type ?? 'Standard' }}
                            </dd>
                            <dt class="col-sm-5">Capacity</dt>
                            <dd class="col-sm-7">
                                {{ $room->roomType->max_occupancy ?? 0 }} Guests
                            </dd>
                        </dl>
                    </div>
                </div>
                
                @if($room->description)
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted">{{ $room->description }}</p>
                </div>
                @endif
                
                @if($room->amenities)
                <div class="mt-3">
                    <h6>Amenities:</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach(explode(',', $room->amenities) as $amenity)
                            <span class="badge bg-light text-dark">{{ trim($amenity) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">Edit Room</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Room Modal --}}
<div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="editRoomLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoomLabel{{ $room->id }}">Edit Room</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Room Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $room->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Room Type <span class="text-danger">*</span></label>
                            <select name="room_type_code" class="form-select" required>
                                <option value="">-- Select Room Type --</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->room_type_code }}" {{ $room->room_type_code == $type->room_type_code ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $room->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Room Modal --}}
<div class="modal fade" id="deleteRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="deleteRoomLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoomLabel{{ $room->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete room <strong>{{ $room->name }}</strong>?</p>
                    <p class="text-muted">This action cannot be undone and will affect all associated reservations.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
