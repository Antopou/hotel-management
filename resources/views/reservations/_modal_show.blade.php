<div class="modal fade" id="reservationShowModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="reservationShowLabel{{ $reservation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reservationShowLabel{{ $reservation->id }}">Reservation Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Reservation Code:</dt>
                    <dd class="col-sm-8">{{ $reservation->reservation_code }}</dd>
                    <dt class="col-sm-4">Guest:</dt>
                    <dd class="col-sm-8">{{ $reservation->guest->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Room:</dt>
                    <dd class="col-sm-8">{{ $reservation->room->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Room Type:</dt>
                    <dd class="col-sm-8">{{ $reservation->room->roomType->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Check-in Date:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($reservation->checkin_date)->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Check-out Date:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($reservation->checkout_date)->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-primary">{{ ucfirst($reservation->status) }}</span>
                    </dd>
                    <dt class="col-sm-4">Number of Guests:</dt>
                    <dd class="col-sm-8">{{ $reservation->number_of_guest }}</dd>
                    <dt class="col-sm-4">Created By:</dt>
                    <dd class="col-sm-8">{{ $reservation->created_by ?? '-' }}</dd>
                    <dt class="col-sm-4">Created At:</dt>
                    <dd class="col-sm-8">{{ $reservation->created_at->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Last Modified:</dt>
                    <dd class="col-sm-8">{{ $reservation->updated_at->format('M d, Y H:i') }}</dd>
                </dl>
                {{-- Optional: Back button --}}
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    onclick="setTimeout(() => bootstrap.Modal.getOrCreateInstance(document.getElementById('frontDeskRoomModal{{ $room->id }}')).show(), 350)">
                    <i class="bi bi-arrow-left"></i> Back to Room
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
