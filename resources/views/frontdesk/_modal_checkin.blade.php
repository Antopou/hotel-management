<!-- Quick Check-in Modal -->
<div class="modal fade" id="quickCheckinModal" tabindex="-1" aria-labelledby="quickCheckinModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('checkins.store') }}" method="POST" id="quickCheckinForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="quickCheckinModalLabel">
                        <i class="bi bi-person-plus"></i> Walk-in Check-in
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Guest Select with Add New --}}
                        <div class="col-md-6">
                            <label for="guest_code" class="form-label">Guest <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="guest_code" id="guest_code" class="form-select" required>
                                    <option value="">-- Select Guest --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->guest_code }}">{{ $guest->name }} ({{ $guest->phone }})</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest">
                                    <i class="bi bi-person-plus-fill"></i>
                                </button>
                            </div>
                        </div>
                        {{-- Room Select --}}
                        <div class="col-md-6">
                            <label for="room_code" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" id="room_code" class="form-select" required>
                                <option value="">-- Select Room --</option>
                                @foreach($rooms->where('status', 'available') as $availableRoom)
                                    <option value="{{ $availableRoom->room_code }}">{{ $availableRoom->name }} ({{ $availableRoom->roomType->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Check-in, Duration, Check-out (calculated) --}}
                        <div class="col-md-6">
                            <label for="checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_nights" id="number_of_nights" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="checkout_date" class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" readonly required>
                        </div>
                        {{-- Number of Guests --}}
                        <div class="col-md-6">
                            <label for="number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        {{-- Notes --}}
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                        </div>
                        {{-- Mark as Checked Out --}}
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_checkout" id="is_checkout" value="1">
                                <label class="form-check-label" for="is_checkout">Mark as Checked Out</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i> Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Guest Modal -->
<div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guests.store') }}" method="POST" id="addGuestForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addGuestLabel">
                        <i class="bi bi-person-plus-fill"></i> Add New Guest
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="guestName" class="form-label">Guest Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="guestName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="guestEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="guestEmail" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="guestPhone" class="form-label">Phone</label>
                        <input type="text" name="tel" id="guestPhone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="guestGender" class="form-label">Gender</label>
                        <select name="gender" id="guestGender" class="form-select">
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>
