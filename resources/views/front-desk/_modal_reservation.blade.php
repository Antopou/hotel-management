<!-- New Reservation Modal -->
<div class="modal fade" id="newReservationModal" tabindex="-1" aria-labelledby="newReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('reservations.store') }}" method="POST" id="newReservationForm">
                @csrf
                <input type="hidden" name="redirect_to" value="front-desk">
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="newReservationModalLabel">
                        <i class="bi bi-calendar-plus"></i> New Reservation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="reservation_guest_code" class="form-label">Guest <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="guest_code" class="form-select guestSelect" id="reservation_guest_code" required>
                                    <option value="">-- Select Guest --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->guest_code }}">
                                            {{ $guest->name }}@if($guest->phone) ({{ $guest->phone }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest">
                                    <i class="bi bi-person-plus-fill"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_room_code" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" id="reservation_room_code" class="form-select" required>
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->room_code }}"
                                        data-next-available="{{ $room->nextAvailableDate()->format('Y-m-d\TH:i') }}"
                                        data-status="{{ $room->status }}">
                                        {{ $room->name }} ({{ $room->roomType->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="room-occupied-warning" style="display: none;">
                            <div class="alert alert-warning mt-2 small">
                                This room is currently occupied. Your reservation will start after the current guest checks out.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="reservation_checkin_date" class="form-control"
                                value="{{ now()->format('Y-m-d\TH:i') }}" required>
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
                        <div class="col-12">
                            <label for="reservation_notes" class="form-label">Notes</label>
                            <textarea name="notes" id="reservation_notes" class="form-control" rows="2"></textarea>
                        </div>
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

@push('scripts')
<script>
$(function() {
    // Room select: update check-in date and show/hide warning
    $('#reservation_room_code').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let suggestedCheckin = selectedOption.data('next-available') || '{{ now()->format('Y-m-d\TH:i') }}';
        $('#reservation_checkin_date').val(suggestedCheckin);

        let roomStatus = selectedOption.data('status') || '';
        if (roomStatus.toLowerCase() === 'occupied') {
            $('#room-occupied-warning').show();
        } else {
            $('#room-occupied-warning').hide();
        }
    });

    // Auto-select room if triggered with data-room-code
    $('#newReservationModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let roomCode = button && button.data('room-code');
        if (roomCode) {
            $('#reservation_room_code').val(roomCode).trigger('change');
        }
    });
    $('#reservation_room_code').trigger('change');

    // Auto-calculate checkout date
    function updateReservationCheckoutDate() {
        let checkin = $('#reservation_checkin_date').val();
        let nights = parseInt($('#reservation_number_of_nights').val()) || 1;
        if (checkin) {
            let date = new Date(checkin);
            date.setDate(date.getDate() + nights);
            let y = date.getFullYear();
            let m = ('0' + (date.getMonth()+1)).slice(-2);
            let d = ('0' + date.getDate()).slice(-2);
            let H = ('0' + date.getHours()).slice(-2);
            let I = ('0' + date.getMinutes()).slice(-2);
            $('#reservation_checkout_date').val(`${y}-${m}-${d}T${H}:${I}`);
        }
    }
    $('#reservation_checkin_date, #reservation_number_of_nights').on('change input', updateReservationCheckoutDate);
    updateReservationCheckoutDate();
});
</script>
@endpush
