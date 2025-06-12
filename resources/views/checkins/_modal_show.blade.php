<div class="modal fade" id="checkinShowModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="checkinShowLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="checkinShowLabel{{ $checkin->id }}">Check-in Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Check-in Code:</dt>
                    <dd class="col-sm-8">{{ $checkin->checkin_code ?? '-' }}</dd>
                    <dt class="col-sm-4">Guest:</dt>
                    <dd class="col-sm-8">{{ $checkin->guest->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Room:</dt>
                    <dd class="col-sm-8">{{ $checkin->room->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Room Type:</dt>
                    <dd class="col-sm-8">{{ $checkin->room->roomType->name ?? '-' }}</dd>
                    <dt class="col-sm-4">Check-in Date:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Check-out Date:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($checkin->checkout_date)->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $checkin->is_checkout ? 'secondary' : 'success' }}">
                            {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                        </span>
                    </dd>
                    <dt class="col-sm-4">Number of Guests:</dt>
                    <dd class="col-sm-8">{{ $checkin->number_of_guest }}</dd>
                    <dt class="col-sm-4">Created By:</dt>
                    <dd class="col-sm-8">{{ $checkin->created_by ?? '-' }}</dd>
                    <dt class="col-sm-4">Created At:</dt>
                    <dd class="col-sm-8">{{ $checkin->created_at->format('M d, Y H:i') }}</dd>
                    <dt class="col-sm-4">Last Modified:</dt>
                    <dd class="col-sm-8">{{ $checkin->updated_at->format('M d, Y H:i') }}</dd>
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
