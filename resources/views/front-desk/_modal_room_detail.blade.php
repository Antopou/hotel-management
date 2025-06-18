@if(isset($room))
<div class="modal fade" id="roomDetailModal{{ $room->id }}" tabindex="-1" aria-labelledby="roomDetailModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fs-4" id="roomDetailModalLabel{{ $room->id }}">
                    <i class="bi bi-door-open me-2"></i>Room {{ $room->name }} Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-size: 1rem;">
                <div class="row g-4">
                    <div class="col-md-4">
                        <img src="{{ $imageUrl ?? '/placeholder.svg?height=300&width=400' }}" 
                             class="img-fluid rounded" alt="{{ $roomTypeName }}" style="width: 100%; height: 250px; object-fit: cover;">
                        <div class="mt-3">
                            <span class="badge bg-{{ $statusColor }} fs-6 px-3 py-2">{{ ucfirst($room->status) }}</span>
                            <span class="badge bg-light text-dark fs-6 px-3 py-2 ms-2">{{ $room->room_code }}</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary mb-3 fs-5">Room Information</h6>
                                <dl class="row">
                                    <dt class="col-sm-5 fs-6">Room Name:</dt>
                                    <dd class="col-sm-7 fs-6 fw-bold">{{ $room->name }}</dd>
                                    <dt class="col-sm-5 fs-6">Room Code:</dt>
                                    <dd class="col-sm-7 fs-6">{{ $room->room_code }}</dd>
                                    <dt class="col-sm-5 fs-6">Room Type:</dt>
                                    <dd class="col-sm-7 fs-6">{{ $roomTypeName }}</dd>
                                    <dt class="col-sm-5 fs-6">Floor:</dt>
                                    <dd class="col-sm-7 fs-6">{{ substr($room->name, -3, 1) }}</dd>
                                    <dt class="col-sm-5 fs-6">Rate:</dt>
                                    <dd class="col-sm-7 fs-6 fw-bold text-success">${{ number_format($roomType->price_per_night ?? 0, 2) }}/night</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success mb-3 fs-5">Amenities</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($roomType)
                                        @if($roomType->has_wifi)
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6"><i class="bi bi-wifi me-1"></i>WiFi</span>
                                        @endif
                                        @if($roomType->has_tv)
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6"><i class="bi bi-tv me-1"></i>TV</span>
                                        @endif
                                        @if($roomType->has_ac)
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6"><i class="bi bi-snow me-1"></i>AC</span>
                                        @endif
                                        @if($roomType->has_balcony)
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6"><i class="bi bi-tree me-1"></i>Balcony</span>
                                        @endif
                                        @if($roomType->has_kitchen)
                                            <span class="badge bg-info bg-opacity-10 text-info fs-6"><i class="bi bi-cup-hot me-1"></i>Kitchen</span>
                                        @endif
                                    @endif
                                </div>
                                @if($roomType && $roomType->description)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-secondary fs-6">Description</h6>
                                    <p class="text-muted fs-6">{{ $roomType->description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($currentCheckin && !$currentCheckin->is_checkout)
                <hr class="my-4">
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold text-danger mb-3 fs-5">
                            <i class="bi bi-person-fill me-2"></i>Current Guest
                        </h6>
                        <div class="alert alert-danger">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong class="fs-6">{{ $currentCheckin->guest->name ?? 'N/A' }}</strong><br>
                                    <small class="fs-6">{{ $currentCheckin->guest->email ?? 'N/A' }}</small><br>
                                    <small class="fs-6">{{ $currentCheckin->guest->phone ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong class="fs-6">Stay Period:</strong><br>
                                    <small class="fs-6">
                                        {{ $currentCheckin->checkin_date ? \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d, Y H:i') : 'N/A' }} - 
                                        {{ $currentCheckin->checkout_date ? \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($nextReservation && in_array(strtolower($room->status), ['available', 'reserved']))
                <hr class="my-4">
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold text-primary mb-3 fs-5">
                            <i class="bi bi-calendar-check me-2"></i>Next Reservation
                        </h6>
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong class="fs-6">{{ $nextReservation->guest->name ?? 'N/A' }}</strong><br>
                                    <small class="fs-6">{{ $nextReservation->guest->email ?? 'N/A' }}</small><br>
                                    <small class="fs-6">{{ $nextReservation->guest->phone ?? 'N/A' }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong class="fs-6">Reservation Period:</strong><br>
                                    <small class="fs-6">
                                        {{ $nextReservation->checkin_date ? \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d, Y') : 'N/A' }} - 
                                        {{ $nextReservation->checkout_date ? \Carbon\Carbon::parse($nextReservation->checkout_date)->format('M d, Y') : 'N/A' }}
                                    </small><br>
                                    <small class="fs-6">Guests: {{ $nextReservation->adults ?? 1 }} adults, {{ $nextReservation->children ?? 0 }} children</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                @if($room->status === 'available')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickCheckinModal" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Quick Check-in
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickReservationModal" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-calendar-plus me-2"></i>Make Reservation
                    </button>
                @elseif($room->status === 'occupied' && $currentCheckin)
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#checkinDetailModal{{ $currentCheckin->id }}" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-info-circle me-2"></i>View Check-in
                    </button>
                    <form action="{{ route('checkins.checkout', $currentCheckin->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to check out this guest?')" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                            <i class="bi bi-box-arrow-right me-2"></i>Check Out
                        </button>
                    </form>
                @endif
                @if($nextReservation)
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reservationDetailModal{{ $nextReservation->id }}" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-calendar-check me-2"></i>View Reservation
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
$(function() {
    // When "New Reservation" modal is opened, auto-select this room
    $('#newReservationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var roomCode = button.data('room-code');
        if(roomCode) {
            $('#reservation_room_code').val(roomCode).trigger('change');
        }
    });
    // When "Walk-in Check-in" modal is opened, auto-select this room
    $('#quickCheckinModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var roomCode = button.data('room-code');
        if(roomCode) {
            $('#room_code').val(roomCode).trigger('change');
        }
    });
});
</script>
@endpush
