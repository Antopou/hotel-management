{{-- room-types/_modals.blade.php --}}
{{-- $roomType is expected --}}

{{-- View Room Type Modal --}}
<div class="modal fade" id="viewRoomTypeModal{{ $roomType->id }}" tabindex="-1" aria-labelledby="viewRoomTypeLabel{{ $roomType->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewRoomTypeLabel{{ $roomType->id }}">Room Type Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Type Name</dt>
                            <dd class="col-sm-7">{{ $roomType->name }}</dd>
                            <dt class="col-sm-5">Base Price</dt>
                            <dd class="col-sm-7">${{ number_format($roomType->base_price ?? 0, 2) }}/night</dd>
                            <dt class="col-sm-5">Capacity</dt>
                            <dd class="col-sm-7">{{ $roomType->capacity ?? 0 }} Guests</dd>
                            <dt class="col-sm-5">Size</dt>
                            <dd class="col-sm-7">{{ $roomType->size ?? 'N/A' }} sqft</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Bed Type</dt>
                            <dd class="col-sm-7">{{ $roomType->bed_type ?? 'Standard' }}</dd>
                            <dt class="col-sm-5">Total Rooms</dt>
                            <dd class="col-sm-7">{{ $roomType->rooms_count ?? 0 }}</dd>
                            <dt class="col-sm-5">Available</dt>
                            <dd class="col-sm-7">{{ $roomType->available_rooms ?? 0 }}</dd>
                            <dt class="col-sm-5">Occupied</dt>
                            <dd class="col-sm-7">{{ $roomType->occupied_rooms ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
                
                @if($roomType->description)
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted">{{ $roomType->description }}</p>
                </div>
                @endif
                
                @if($roomType->amenities)
                <div class="mt-3">
                    <h6>Standard Amenities:</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach(explode(',', $roomType->amenities) as $amenity)
                            <span class="badge bg-light text-dark">{{ trim($amenity) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRoomTypeModal{{ $roomType->id }}">Edit Room Type</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Room Type Modal --}}
<div class="modal fade" id="editRoomTypeModal{{ $roomType->id }}" tabindex="-1" aria-labelledby="editRoomTypeLabel{{ $roomType->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('room-types.update', $roomType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoomTypeLabel{{ $roomType->id }}">Edit Room Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $roomType->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Base Price <span class="text-danger">*</span></label>
                            <input type="number" name="base_price" class="form-control" value="{{ $roomType->base_price }}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacity <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" class="form-control" value="{{ $roomType->capacity }}" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Size (sqft)</label>
                            <input type="number" name="size" class="form-control" value="{{ $roomType->size }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bed Type</label>
                            <select name="bed_type" class="form-select">
                                <option value="Single" {{ $roomType->bed_type == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Double" {{ $roomType->bed_type == 'Double' ? 'selected' : '' }}>Double</option>
                                <option value="Queen" {{ $roomType->bed_type == 'Queen' ? 'selected' : '' }}>Queen</option>
                                <option value="King" {{ $roomType->bed_type == 'King' ? 'selected' : '' }}>King</option>
                                <option value="Twin" {{ $roomType->bed_type == 'Twin' ? 'selected' : '' }}>Twin</option>
                                <option value="Suite" {{ $roomType->bed_type == 'Suite' ? 'selected' : '' }}>Suite</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $roomType->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Standard Amenities (comma separated)</label>
                            <input type="text" name="amenities" class="form-control" value="{{ $roomType->amenities }}" placeholder="WiFi, TV, AC, Mini Bar, Safe">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Room Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Room Type Modal --}}
<div class="modal fade" id="deleteRoomTypeModal{{ $roomType->id }}" tabindex="-1" aria-labelledby="deleteRoomTypeLabel{{ $roomType->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('room-types.destroy', $roomType->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoomTypeLabel{{ $roomType->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete room type <strong>{{ $roomType->name }}</strong>?</p>
                    <p class="text-muted">This action cannot be undone and will affect all rooms of this type.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
