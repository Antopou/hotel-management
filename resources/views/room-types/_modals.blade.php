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
                <div class="row mb-3">
                    <div class="col-md-4 text-center">
                        @if($roomType->image)
                            <img src="{{ asset('storage/' . $roomType->image) }}" alt="{{ $roomType->name }}" class="img-fluid rounded shadow" style="max-height:180px;">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-fluid rounded shadow" style="max-height:180px;">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <dl class="row">
                            <dt class="col-sm-5">Type Name</dt>
                            <dd class="col-sm-7">{{ $roomType->name }}</dd>
                            <dt class="col-sm-5">Base Price</dt>
                            <dd class="col-sm-7">${{ number_format($roomType->price_per_night ?? 0, 2) }}/night</dd>
                            <dt class="col-sm-5">Capacity</dt>
                            <dd class="col-sm-7">{{ $roomType->max_occupancy ?? 0 }} Guests</dd>
                            <dt class="col-sm-5">Size</dt>
                            <dd class="col-sm-7">{{ $roomType->size ?? 'N/A' }} sqft</dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-md-6">
                        @if($roomType->has_wifi || $roomType->has_tv || $roomType->has_ac || $roomType->has_breakfast || $roomType->has_parking)
                        <h6>Amenities:</h6>
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            @if($roomType->has_wifi)
                                <span class="badge bg-light text-dark"><i class="bi bi-wifi"></i> WiFi</span>
                            @endif
                            @if($roomType->has_tv)
                                <span class="badge bg-light text-dark"><i class="bi bi-tv"></i> TV</span>
                            @endif
                            @if($roomType->has_ac)
                                <span class="badge bg-light text-dark"><i class="bi bi-snow"></i> AC</span>
                            @endif
                            @if($roomType->has_breakfast)
                                <span class="badge bg-light text-dark"><i class="bi bi-cup-hot"></i> Breakfast</span>
                            @endif
                            @if($roomType->has_parking)
                                <span class="badge bg-light text-dark"><i class="bi bi-car-front"></i> Parking</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @if($roomType->description)
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted">{{ $roomType->description }}</p>
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
            <form action="{{ route('room-types.update', $roomType->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editRoomTypeLabel{{ $roomType->id }}">
                        <i class="bi bi-pencil-square"></i> Edit Room Type
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="editRoomTypeImage{{ $roomType->id }}" class="form-label">Image</label>
                            <input type="file" name="image" id="editRoomTypeImage{{ $roomType->id }}" class="form-control" accept="image/*">
                            @if($roomType->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $roomType->image) }}" alt="{{ $roomType->name }}" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="editRoomTypeName{{ $roomType->id }}" class="form-label">Room Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editRoomTypeName{{ $roomType->id }}" class="form-control" value="{{ $roomType->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editRoomTypePrice{{ $roomType->id }}" class="form-label">Price per Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_night" id="editRoomTypePrice{{ $roomType->id }}" class="form-control" step="0.01" min="0" value="{{ $roomType->price_per_night }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editRoomTypeCapacity{{ $roomType->id }}" class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                            <input type="number" name="max_occupancy" id="editRoomTypeCapacity{{ $roomType->id }}" class="form-control" min="1" value="{{ $roomType->max_occupancy }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editRoomTypeSize{{ $roomType->id }}" class="form-label">Size</label>
                            <select name="size" id="editRoomTypeSize{{ $roomType->id }}" class="form-select">
                                <option value="">Select Size</option>
                                @foreach([200,250,300,350,400,450,500,550,600,650,700,750,800,850,900,1000] as $size)
                                    <option value="{{ $size }}" {{ $roomType->size == $size ? 'selected' : '' }}>{{ $size }} sqft</option>
                                @endforeach
                                <option value="Other" {{ $roomType->size && !in_array($roomType->size, [200,250,300,350,400,450,500,550,600,650,700,750,800,850,900,1000]) ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editRoomTypeBedType{{ $roomType->id }}" class="form-label">Bed Type</label>
                            <select name="bed_type" id="editRoomTypeBedType{{ $roomType->id }}" class="form-select">
                                <option value="">Select Bed Type</option>
                                @foreach(['Single','Double','Queen','King','Twin','Suite'] as $bed)
                                    <option value="{{ $bed }}" {{ $roomType->bed_type == $bed ? 'selected' : '' }}>{{ $bed }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Amenities</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_wifi" id="editHasWifi{{ $roomType->id }}" value="1" {{ $roomType->has_wifi ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editHasWifi{{ $roomType->id }}">
                                            <i class="bi bi-wifi"></i> WiFi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_tv" id="editHasTv{{ $roomType->id }}" value="1" {{ $roomType->has_tv ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editHasTv{{ $roomType->id }}">
                                            <i class="bi bi-tv"></i> TV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_ac" id="editHasAc{{ $roomType->id }}" value="1" {{ $roomType->has_ac ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editHasAc{{ $roomType->id }}">
                                            <i class="bi bi-snow"></i> Air Conditioning
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_breakfast" id="editHasBreakfast{{ $roomType->id }}" value="1" {{ $roomType->has_breakfast ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editHasBreakfast{{ $roomType->id }}">
                                            <i class="bi bi-cup-hot"></i> Breakfast
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_parking" id="editHasParking{{ $roomType->id }}" value="1" {{ $roomType->has_parking ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editHasParking{{ $roomType->id }}">
                                            <i class="bi bi-car-front"></i> Parking
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="editRoomTypeDescription{{ $roomType->id }}" class="form-label">Description</label>
                            <textarea name="description" id="editRoomTypeDescription{{ $roomType->id }}" class="form-control" rows="3" placeholder="Describe the room type features and amenities">{{ $roomType->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Update Room Type
                    </button>
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
