{{-- resources/views/checkins/_modal_create.blade.php --}}
<div class="modal fade" id="createCheckinModal" tabindex="-1" aria-labelledby="createCheckinLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('checkins.store') }}" method="POST" id="createCheckinForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="createCheckinLabel">Add New Check-in</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="guestSelect" class="form-label">Guest <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="guest_code" class="form-select" required id="guestSelect">
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
                            <label for="roomSelect" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" class="form-select" required id="roomSelect">
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_nights" id="number_of_nights" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="checkout_date" class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label for="number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
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

{{-- Add Guest Modal --}}
@include('guests._modal_create')

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Auto-calculate checkout date
    function updateCheckoutDate() {
        let checkin = document.getElementById('checkin_date').value;
        let nights = parseInt(document.getElementById('number_of_nights').value) || 1;
        if (checkin) {
            let date = new Date(checkin);
            date.setDate(date.getDate() + nights);
            let y = date.getFullYear();
            let m = ('0' + (date.getMonth()+1)).slice(-2);
            let d = ('0' + date.getDate()).slice(-2);
            let H = ('0' + date.getHours()).slice(-2);
            let I = ('0' + date.getMinutes()).slice(-2);
            document.getElementById('checkout_date').value = `${y}-${m}-${d}T${H}:${I}`;
        }
    }
    document.getElementById('checkin_date').addEventListener('change', updateCheckoutDate);
    document.getElementById('number_of_nights').addEventListener('input', updateCheckoutDate);
    updateCheckoutDate();

    // AJAX Guest Add
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
                    $('#guestSelect').append(newOption).val(response.guest_code);
                    $('#guestSelect').trigger('change');
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
});
</script>
@endpush
