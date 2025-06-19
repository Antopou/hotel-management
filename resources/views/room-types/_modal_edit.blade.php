{{-- resources/views/room-types/_modal_edit.blade.php --}}
<div class="modal fade" id="editRoomTypeModal{{ $roomType->id }}" tabindex="-1" aria-labelledby="editRoomTypeLabel{{ $roomType->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('room-types.update', $roomType->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editRoomTypeLabel{{ $roomType->id }}">
                        <i class="bi bi-house-gear"></i> Edit Room Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
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
                            <label for="editRoomTypeBeds{{ $roomType->id }}" class="form-label">Number of Beds</label>
                            <input type="number" name="number_of_beds" id="editRoomTypeBeds{{ $roomType->id }}" class="form-control" min="1" value="{{ $roomType->number_of_beds }}">
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
                            <textarea name="description" id="editRoomTypeDescription{{ $roomType->id }}" class="form-control" rows="3">{{ $roomType->description }}</textarea>
                        </div>
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
