<!-- Quick Check-in Modal -->
<div class="modal fade" id="quickCheckinModal" tabindex="-1" aria-labelledby="quickCheckinModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('checkins.store') }}" method="POST" id="quickCheckinForm">
                @csrf
                <input type="hidden" name="redirect_to" value="front-desk">
                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="quickCheckinModalLabel">
                        <i class="bi bi-person-plus"></i> Walk-in Check-in
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Guest Select with Add New (uses guestSelect class) -->
                        <div class="col-md-6">
                            <label for="quick_guest_code" class="form-label">Guest <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="guest_code" class="form-select guestSelect" id="quick_guest_code" required>
                                    <option value="">-- Select Guest --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->guest_code }}">
                                            {{ $guest->name }}@if($guest->tel) ({{ $guest->tel }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest">
                                    <i class="bi bi-person-plus-fill"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Room Select (ALL ROOMS, occupied disabled and labeled) -->
                        <div class="col-md-6">
                            <label for="quick_room_code" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" class="form-select" id="quick_room_code" required>
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    @php
                                        $currentCheckin = $room->currentCheckin();
                                        $isOccupied = $currentCheckin && !$currentCheckin->is_checkout;
                                        $occupiedUntil = $isOccupied ? \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('Y-m-d\TH:i') : '';
                                    @endphp
                                    <option
                                        value="{{ $room->room_code }}"
                                        data-status="{{ $room->status }}"
                                        data-occupied="{{ $isOccupied ? '1' : '0' }}"
                                        data-occupied-until="{{ $occupiedUntil }}"
                                        {{ $isOccupied ? 'disabled' : '' }}
                                    >
                                        {{ $room->name }} ({{ $room->roomType->name ?? 'N/A' }}){{ $isOccupied ? ' - OCCUPIED until ' . \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Warning if occupied -->
                        <div id="quick-room-occupied-warning" style="display:none;">
                            <div class="alert alert-danger mt-2 small"></div>
                        </div>
                        <!-- Check-in, Duration, Check-out (calculated) -->
                        <div class="col-md-6">
                            <label for="quick_checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="quick_checkin_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_nights" id="quick_number_of_nights" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_checkout_date" class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="quick_checkout_date" class="form-control" readonly required>
                        </div>
                        <!-- Number of Guests -->
                        <div class="col-md-6">
                            <label for="quick_number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="quick_number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        <!-- Notes -->
                        <div class="col-12">
                            <label for="quick_notes" class="form-label">Notes</label>
                            <textarea name="notes" id="quick_notes" class="form-control" rows="2"></textarea>
                        </div>
                        <!-- Mark as Checked Out -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_checkout" id="quick_is_checkout" value="1">
                                <label class="form-check-label" for="quick_is_checkout">Mark as Checked Out</label>
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

@push('scripts')
<script>
$(function() {
    // --- Quick Check-in Modal: Auto-calculate checkout date ---
    function updateQuickCheckoutDate() {
        let checkin = $('#quick_checkin_date').val();
        let nights = parseInt($('#quick_number_of_nights').val()) || 1;
        if (checkin) {
            let date = new Date(checkin);
            date.setDate(date.getDate() + nights);
            let y = date.getFullYear();
            let m = ('0' + (date.getMonth()+1)).slice(-2);
            let d = ('0' + date.getDate()).slice(-2);
            let H = ('0' + date.getHours()).slice(-2);
            let I = ('0' + date.getMinutes()).slice(-2);
            $('#quick_checkout_date').val(`${y}-${m}-${d}T${H}:${I}`);
        }
    }
    $('#quick_checkin_date, #quick_number_of_nights').on('change input', updateQuickCheckoutDate);
    updateQuickCheckoutDate();

    // Show warning if occupied room is selected
    $('#quick_room_code').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let isOccupied = selectedOption.data('occupied') === 1 || selectedOption.data('occupied') === '1';
        let occupiedUntil = selectedOption.data('occupied-until') || '';
        if (isOccupied) {
            $('#quick-room-occupied-warning .alert').text(
                "This room is currently occupied and can't be checked in. Available after: " +
                (occupiedUntil ? new Date(occupiedUntil).toLocaleString() : '')
            );
            $('#quick-room-occupied-warning').show();
        } else {
            $('#quick-room-occupied-warning').hide();
        }
    });

    // On modal open, preselect room if triggered with data-room-code
    $('#quickCheckinModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let roomCode = button && button.data('room-code');
        if (roomCode) {
            $('#quick_room_code').val(roomCode).trigger('change');
        } else {
            $('#quick_room_code').val('').trigger('change');
        }
    });
});
</script>
@endpush
