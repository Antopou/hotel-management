{{-- resources/views/room-types/_modal_create.blade.php --}}
<div class="modal fade" id="createRoomTypeModal" tabindex="-1" aria-labelledby="createRoomTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createRoomTypeLabel">
                        <i class="bi bi-house-add"></i> Add New Room Type
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="roomTypeImage" class="form-label">Image</label>
                            <input type="file" name="image" id="roomTypeImage" class="form-control" accept="image/*">
                        </div>
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
                            <label for="roomTypeSize" class="form-label">Size</label>
                            <select name="size" id="roomTypeSize" class="form-select">
                                <option value="">Select Size</option>
                                <option value="200">200 sqft</option>
                                <option value="250">250 sqft</option>
                                <option value="300">300 sqft</option>
                                <option value="350">350 sqft</option>
                                <option value="400">400 sqft</option>
                                <option value="450">450 sqft</option>
                                <option value="500">500 sqft</option>
                                <option value="550">550 sqft</option>
                                <option value="600">600 sqft</option>
                                <option value="650">650 sqft</option>
                                <option value="700">700 sqft</option>
                                <option value="750">750 sqft</option>
                                <option value="800">800 sqft</option>
                                <option value="850">850 sqft</option>
                                <option value="900">900 sqft</option>
                                <option value="1000">1000 sqft</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="roomTypeBedType" class="form-label">Bed Type</label>
                            <select name="bed_type" id="roomTypeBedType" class="form-select">
                                <option value="">Select Bed Type</option>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Queen">Queen</option>
                                <option value="King">King</option>
                                <option value="Twin">Twin</option>
                                <option value="Suite">Suite</option>
                            </select>
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
