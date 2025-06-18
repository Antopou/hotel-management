{{-- resources/views/room-types/_modal_create.blade.php --}}
<div class="modal fade" id="createRoomTypeModal" tabindex="-1" aria-labelledby="createRoomTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('room-types.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createRoomTypeLabel">
                        <i class="bi bi-house-add"></i> Add New Room Type
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="roomTypeName" class="form-label">Room Type Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="roomTypeName" class="form-control" required placeholder="e.g., Deluxe Suite">
                        </div>
                        <div class="col-md-6">
                            <label for="roomTypePrice" class="form-label">Price per Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_night" id="roomTypePrice" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="roomTypeCapacity" class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                            <input type="number" name="max_occupancy" id="roomTypeCapacity" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="roomTypeBeds" class="form-label">Number of Beds</label>
                            <input type="number" name="number_of_beds" id="roomTypeBeds" class="form-control" min="1" value="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Amenities</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_wifi" id="hasWifi" value="1">
                                        <label class="form-check-label" for="hasWifi">
                                            <i class="bi bi-wifi"></i> WiFi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_tv" id="hasTv" value="1">
                                        <label class="form-check-label" for="hasTv">
                                            <i class="bi bi-tv"></i> TV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_ac" id="hasAc" value="1">
                                        <label class="form-check-label" for="hasAc">
                                            <i class="bi bi-snow"></i> Air Conditioning
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_breakfast" id="hasBreakfast" value="1">
                                        <label class="form-check-label" for="hasBreakfast">
                                            <i class="bi bi-cup-hot"></i> Breakfast
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_parking" id="hasParking" value="1">
                                        <label class="form-check-label" for="hasParking">
                                            <i class="bi bi-car-front"></i> Parking
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="roomTypeDescription" class="form-label">Description</label>
                            <textarea name="description" id="roomTypeDescription" class="form-control" rows="3" placeholder="Describe the room type features and amenities"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i> Create Room Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
