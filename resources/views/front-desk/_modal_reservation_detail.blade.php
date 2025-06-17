<div class="modal fade" id="reservationDetailModal{{ $nextReservation->id }}" tabindex="-1" aria-labelledby="reservationDetailModalLabel{{ $nextReservation->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="reservationDetailModalLabel{{ $nextReservation->id }}">
                    <i class="bi bi-calendar-check"></i> Reservation Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Guest Name:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->guest->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Phone:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->guest->phone ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->guest->email ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Room:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->room->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Room Type:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->room->roomType->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Check-in:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d, Y H:i') }}</dd>

                    <dt class="col-sm-4">Check-out:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($nextReservation->checkout_date)->format('M d, Y H:i') }}</dd>

                    <dt class="col-sm-4">Number of Guests:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->number_of_guest }}</dd>

                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-primary">{{ ucfirst($nextReservation->status) }}</span>
                    </dd>

                    <dt class="col-sm-4">Notes:</dt>
                    <dd class="col-sm-8">{{ $nextReservation->notes ?? '-' }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>