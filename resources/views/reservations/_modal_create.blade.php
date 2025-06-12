{{-- resources/views/reservations/_modal_create.blade.php --}}
<div class="modal fade" id="createReservationModal" tabindex="-1" aria-labelledby="createReservationLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createReservationLabel">Add New Reservation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="reservation_guestSelect" class="form-label">Guest <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="guest_code" class="form-select" required id="reservation_guestSelect">
                                    <option value="">-- Select Guest --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest">
                                    <i class="bi bi-person-plus-fill"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_roomSelect" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" class="form-select" required id="reservation_roomSelect">
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="reservation_checkin_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_nights" id="reservation_number_of_nights" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_checkout_date" class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="reservation_checkout_date" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="reservation_number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-12">
                            <label for="reservation_status" class="form-label">Status</label>
                            <select name="status" id="reservation_status" class="form-select">
                                <option value="pending" selected>Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="checked-in">Checked In</option>
                                <option value="checked-out">Checked Out</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Optionally, add the Add Guest modal here if you want --}}
@include('guests._modal_create')
