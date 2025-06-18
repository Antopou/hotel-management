<!-- Quick Check-in Modal -->
<div class="modal fade" id="quickCheckinModal" tabindex="-1" aria-labelledby="quickCheckinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fs-4" id="quickCheckinModalLabel">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Quick Check-in
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('checkins.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="font-size: 1rem;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="guest_id" class="form-label fw-semibold">Guest <span class="text-danger">*</span></label>
                            <select class="form-select" id="guest_id" name="guest_id" required style="font-size: 1rem; padding: 0.75rem;">
                                <option value="">Select Guest</option>
                                @foreach($guests ?? [] as $guest)
                                    <option value="{{ $guest->id }}">{{ $guest->name }} - {{ $guest->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="room_id" class="form-label fw-semibold">Room <span class="text-danger">*</span></label>
                            <select class="form-select" id="room_id" name="room_id" required style="font-size: 1rem; padding: 0.75rem;">
                                <option value="">Select Room</option>
                                @foreach($rooms->where('status', 'available') ?? [] as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} - {{ $room->roomType->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="checkin_date" class="form-label fw-semibold">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="checkin_date" name="checkin_date" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required style="font-size: 1rem; padding: 0.75rem;">
                        </div>
                        <div class="col-md-6">
                            <label for="checkout_date" class="form-label fw-semibold">Check-out Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="checkout_date" name="checkout_date" 
                                   value="{{ now()->addDay()->format('Y-m-d\TH:i') }}" required style="font-size: 1rem; padding: 0.75rem;">
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label fw-semibold">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Any special requests or notes..." style="font-size: 1rem; padding: 0.75rem;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-check-circle me-2"></i>Check In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Guest Modal (unchanged) -->
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

    // Show warning if occupied room is selected (shouldn't happen if disabled, but safe)
    $('#room_code').on('change', function() {
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

    // --- Add Guest AJAX (for quick check-in modal) ---
    $('#addGuestForm').submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        var data = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
            success: function(response) {
                if (response && response.guest_code && response.name) {
                    var newOption = new Option(response.name, response.guest_code, true, true);
                    $('#guest_code').append(newOption).val(response.guest_code);
                    $('#guest_code').trigger('change');
                    $('#addGuestModal').modal('hide');
                    $form[0].reset();
                } else {
                    alert('Guest added, but no response data. Please refresh to see the new guest if not automatically selected.');
                    $('#addGuestModal').modal('hide');
                }
            },
            error: function(xhr) {
                alert('Failed to add guest. Please check your input or console for details.');
                console.error('AJAX error:', xhr.responseText);
            }
        });
    });

    // On modal open, preselect room if triggered with data-room-code
    $('#quickCheckinModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let roomCode = button && button.data('room-code');
        if (roomCode) {
            $('#room_code').val(roomCode).trigger('change');
        } else {
            $('#room_code').val('').trigger('change');
        }
    });
});
</script>
@endpush
