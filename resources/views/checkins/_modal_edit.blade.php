{{-- resources/views/checkins/_modal_edit.blade.php --}}
<div class="modal fade" id="editCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="editCheckinLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('checkins.update', $checkin->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editCheckinLabel{{ $checkin->id }}">
                        <i class="bi bi-pencil-square"></i> Edit Check-in
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_guest_{{ $checkin->id }}" class="form-label">Guest <span class="text-danger">*</span></label>
                            <select name="guest_code" class="form-select" required id="edit_guest_{{ $checkin->id }}">
                                @foreach ($guests as $guest)
                                    <option value="{{ $guest->guest_code }}" {{ $checkin->guest_code == $guest->guest_code ? 'selected' : '' }}>
                                        {{ $guest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_room_{{ $checkin->id }}" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" class="form-select" required id="edit_room_{{ $checkin->id }}">
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}" {{ $checkin->room_code == $room->room_code ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_checkin_date_{{ $checkin->id }}" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="edit_checkin_date_{{ $checkin->id }}" 
                                   class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_checkout_date_{{ $checkin->id }}" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkout_date" id="edit_checkout_date_{{ $checkin->id }}" 
                                   class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkout_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_number_of_guest_{{ $checkin->id }}" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="edit_number_of_guest_{{ $checkin->id }}" 
                                   class="form-control" min="1" value="{{ $checkin->number_of_guest }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_status_{{ $checkin->id }}" class="form-label">Status</label>
                            <select name="is_checkout" id="edit_status_{{ $checkin->id }}" class="form-select">
                                <option value="0" {{ !$checkin->is_checkout ? 'selected' : '' }}>In Stay</option>
                                <option value="1" {{ $checkin->is_checkout ? 'selected' : '' }}>Checked Out</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_notes_{{ $checkin->id }}" class="form-label">Notes</label>
                            <textarea name="notes" id="edit_notes_{{ $checkin->id }}" class="form-control" rows="2">{{ $checkin->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
