<div class="modal fade" id="checkinDetailModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="checkinDetailModalLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="checkinDetailModalLabel{{ $checkin->id }}">
                    <i class="bi bi-person-badge"></i> Check-in Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row fs-6">
                    <dt class="col-sm-4">Guest Name:</dt>
                    <dd class="col-sm-8">{{ $checkin->guest->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Phone:</dt>
                    <dd class="col-sm-8">{{ $checkin->guest->tel ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8">{{ $checkin->guest->email ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Room:</dt>
                    <dd class="col-sm-8">{{ $checkin->room->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Room Type:</dt>
                    <dd class="col-sm-8">{{ $checkin->room->roomType->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Check-in:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y H:i') }}</dd>

                    <dt class="col-sm-4">Check-out:</dt>
                    <dd class="col-sm-8">{{ $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date)->format('M d, Y H:i') : '-' }}</dd>

                    <dt class="col-sm-4">Number of Guests:</dt>
                    <dd class="col-sm-8">{{ $checkin->number_of_guest }}</dd>

                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}">
                            {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Notes:</dt>
                    <dd class="col-sm-8">{{ $checkin->notes ?? '-' }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>