{{-- resources/views/guests/_modal_edit.blade.php --}}
<div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="editGuestLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guests.update', $guest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editGuestLabel{{ $guest->id }}">
                        <i class="bi bi-person-gear"></i> Edit Guest
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editGuestName{{ $guest->id }}" class="form-label">Guest Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editGuestName{{ $guest->id }}" class="form-control" value="{{ $guest->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGuestEmail{{ $guest->id }}" class="form-label">Email</label>
                        <input type="email" name="email" id="editGuestEmail{{ $guest->id }}" class="form-control" value="{{ $guest->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="editGuestPhone{{ $guest->id }}" class="form-label">Phone</label>
                        <input type="text" name="tel" id="editGuestPhone{{ $guest->id }}" class="form-control" value="{{ $guest->tel }}">
                    </div>
                    <div class="mb-3">
                        <label for="editGuestGender{{ $guest->id }}" class="form-label">Gender</label>
                        <select name="gender" id="editGuestGender{{ $guest->id }}" class="form-select">
                            <option value="">-- Select --</option>
                            <option value="Male" {{ $guest->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $guest->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $guest->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editGuestAddress{{ $guest->id }}" class="form-label">Address</label>
                        <textarea name="address" id="editGuestAddress{{ $guest->id }}" class="form-control" rows="2">{{ $guest->address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-2"></i> Update
                    </button>
                    <!-- Delete button -->
                    <form action="{{ route('guests.destroy', $guest->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this guest?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i> Delete
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
