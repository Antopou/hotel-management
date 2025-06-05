{{-- reservations/_modals.blade.php --}}
{{-- $reservation is expected --}}

{{-- View Modal --}}
<div class="modal fade" id="viewReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="viewReservationLabel{{ $reservation->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReservationLabel{{ $reservation->id }}">Reservation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Guest</dt>
                    <dd class="col-sm-8">{{ $reservation->guest->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Room</dt>
                    <dd class="col-sm-8">{{ $reservation->room->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Check-in Date</dt>
                    <dd class="col-sm-8">{{ $reservation->checkin_date }}</dd>
                    <dt class="col-sm-4">Check-out Date</dt>
                    <dd class="col-sm-8">{{ $reservation->checkout_date }}</dd>
                    <dt class="col-sm-4">Guests</dt>
                    <dd class="col-sm-8">{{ $reservation->number_of_guest }}</dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">{{ ucfirst($reservation->status) }}</dd>
                    <dt class="col-sm-4">Cancellation Reason</dt>
                    <dd class="col-sm-8">{{ $reservation->reason ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Cancelled Date</dt>
                    <dd class="col-sm-8">{{ $reservation->cancelled_date ?? 'N/A' }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="editReservationLabel{{ $reservation->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editReservationLabel{{ $reservation->id }}">Edit Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Guest (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Guest</label>
                        <select class="form-select" name="guest_code" disabled>
                            <option value="{{ $reservation->guest->guest_code }}">{{ $reservation->guest->name }}</option>
                        </select>
                        <input type="hidden" name="guest_code" value="{{ $reservation->guest->guest_code }}">
                    </div>
                    <!-- Room (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Room</label>
                        <select class="form-select" name="room_code" disabled>
                            <option value="{{ $reservation->room->room_code }}">{{ $reservation->room->name }}</option>
                        </select>
                        <input type="hidden" name="room_code" value="{{ $reservation->room->room_code }}">
                    </div>
                    <!-- Check-in, Duration, Check-out -->
                    <div class="mb-3">
                        <label class="form-label">Check-in Date</label>
                        <input type="datetime-local" name="checkin_date"
                            class="form-control"
                            value="{{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y-m-d\TH:i') }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Number of Nights</label>
                        <input type="number" name="number_of_nights"
                            class="form-control"
                            min="1"
                            value="{{ \Carbon\Carbon::parse($reservation->checkin_date)->diffInDays(\Carbon\Carbon::parse($reservation->checkout_date)) ?: 1 }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-out Date</label>
                        <input type="datetime-local" name="checkout_date"
                            class="form-control"
                            value="{{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y-m-d\TH:i') }}"
                            readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Number of Guests</label>
                        <input type="number" name="number_of_guest"
                            class="form-control"
                            min="1"
                            value="{{ $reservation->number_of_guest }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" @if($reservation->status === 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if($reservation->status === 'confirmed') selected @endif>Confirmed</option>
                            <option value="checked-in" @if($reservation->status === 'checked-in') selected @endif>Checked In</option>
                            <option value="checked-out" @if($reservation->status === 'checked-out') selected @endif>Checked Out</option>
                            <option value="cancelled" @if($reservation->status === 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="deleteReservationLabel{{ $reservation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteReservationLabel{{ $reservation->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the reservation for <strong>{{ $reservation->guest->name ?? 'N/A' }}</strong>?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
