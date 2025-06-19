{{-- resources/views/guests/_modal_create.blade.php --}}
<div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg for large size -->
        <div class="modal-content">
            <form action="{{ route('guests.store') }}" method="POST" id="addGuestForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addGuestLabel">Add New Guest</h5>
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
