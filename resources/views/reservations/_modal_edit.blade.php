{{-- resources/views/reservations/_modal_edit.blade.php --}}
<div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="editReservationLabel{{ $reservation->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editReservationLabel{{ $reservation->id }}">
                        <i class="bi bi-calendar-event"></i> Edit Reservation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_res_guest_{{ $reservation->id }}" class="form-label">Guest <span class="text-danger">*</span></label>
                            <select name="guest_code" class="form-select" required id="edit_res_guest_{{ $reservation->id }}">
                                @foreach ($guests as $guest)
                                    <option value="{{ $guest->guest_code }}" {{ $reservation->guest_code == $guest->guest_code ? 'selected' : '' }}>
                                        {{ $guest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_res_room_{{ $reservation->id }}" class="form-label">Room <span class="text-danger">*</span></label>
                            <select name="room_code" class="form-select" required id="edit_res_room_{{ $reservation->id }}">
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}" {{ $reservation->room_code == $room->room_code ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_res_checkin_{{ $reservation->id }}" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkin_date" id="edit_res_checkin_{{ $reservation->id }}" 
                                   class="form-control" value="{{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_res_checkout_{{ $reservation->id }}" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="checkout_date" id="edit_res_checkout_{{ $reservation->id }}" 
                                   class="form-control" value="{{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_res_guests_{{ $reservation->id }}" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_guest" id="edit_res_guests_{{ $reservation->id }}" 
                                   class="form-control" min="1" value="{{ $reservation->number_of_guest }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_res_status_{{ $reservation->id }}" class="form-label">Status</label>
                            <select name="status" id="edit_res_status_{{ $reservation->id }}" class="form-select">
                                <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="checked-in" {{ $reservation->status == 'checked-in' ? 'selected' : '' }}>Checked In</option>
                                <option value="checked-out" {{ $reservation->status == 'checked-out' ? 'selected' : '' }}>Checked Out</option>
                                <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_res_notes_{{ $reservation->id }}" class="form-label">Notes</label>
                            <textarea name="notes" id="edit_res_notes_{{ $reservation->id }}" class="form-control" rows="2">{{ $reservation->notes }}</textarea>
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
