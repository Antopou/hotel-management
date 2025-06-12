
<!-- New Reservation Modal -->
<div class="modal fade" id="newReservationModal" tabindex="-1" aria-labelledby="newReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newReservationModalLabel">
                    <i class="bi bi-calendar-plus"></i> New Reservation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reservation_room_code" class="form-label">Room</label>
                        <select name="room_code" id="reservation_room_code" class="form-select" required>
                            <option value="">Select Room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->room_code }}">{{ $room->name }} ({{ $room->roomType->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reservation_guest_code" class="form-label">Guest</label>
                        <select name="guest_code" id="reservation_guest_code" class="form-select" required>
                            <option value="">Select Guest</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->guest_code }}">{{ $guest->name }} ({{ $guest->phone }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <a href="{{ route('guests.create') }}" target="_blank">Add new guest</a>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="checkin_date" class="form-label">Check-in Date</label>
                            <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="checkout_date" class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="number_of_guest" class="form-label">Number of Guests</label>
                            <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_status" class="form-label">Status</label>
                            <select name="status" id="reservation_status" class="form-select">
                                <option value="confirmed">Confirmed</option>
                                <option value="tentative">Tentative</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reservation_notes" class="form-label">Notes</label>
                        <textarea name="notes" id="reservation_notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
