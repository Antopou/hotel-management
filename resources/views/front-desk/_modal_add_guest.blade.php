<!-- Add Guest Modal (universal, use class="addGuestForm"!) -->
<div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guests.store') }}" method="POST" class="addGuestForm">
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
    // Universal Add Guest AJAX for all forms (works anywhere with class="addGuestForm")
    $(document).on('submit', '.addGuestForm', function(e) {
        e.preventDefault();
        var $form = $(this);
        var data = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('input[name="_token"]', $form).val()},
            success: function(response) {
                if (response && response.guest_code && response.name) {
                    // Update all .guestSelect selects everywhere
                    $('.guestSelect').each(function() {
                        var $select = $(this);
                        var exists = $select.find('option[value="' + response.guest_code + '"]').length > 0;
                        if (!exists) {
                            var newOption = new Option(response.name, response.guest_code, true, true);
                            $select.append(newOption).val(response.guest_code).trigger('change');
                        } else {
                            $select.val(response.guest_code).trigger('change');
                        }
                    });
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
