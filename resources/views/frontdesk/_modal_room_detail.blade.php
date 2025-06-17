<div class="modal fade" id="roomDetailModal{{ $room->id }}" tabindex="-1" aria-labelledby="roomDetailModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomDetailModalLabel{{ $room->id }}">
                    <i class="bi bi-door-open"></i> Room {{ $room->name }} Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <img src="{{ $imageUrl }}" class="img-fluid rounded mb-3" alt="{{ $roomTypeName }}">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-muted">ROOM INFORMATION</h6>
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">Room Code:</dt>
                                    <dd class="col-sm-7">{{ $room->room_code }}</dd>
                                    <dt class="col-sm-5">Type:</dt>
                                    <dd class="col-sm-7">{{ $roomTypeName }}</dd>
                                    <dt class="col-sm-5">Status:</dt>
                                    <dd class="col-sm-7">
                                        <span class="badge bg-{{ $statusColor }} text-uppercase">
                                            {{ $room->status }}
                                        </span>
                                    </dd>
                                    <dt class="col-sm-5">Capacity:</dt>
                                    <dd class="col-sm-7">{{ $roomType->capacity ?? 'N/A' }} persons</dd>
                                    <dt class="col-sm-5">Price:</dt>
                                    <dd class="col-sm-7">{{ $roomType ? ($roomType->price) : 'N/A' }}/night</dd>
                                </dl>
                            </div>
                        </div>
                        @if($roomType)
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-muted">AMENITIES</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($roomType->has_wifi)
                                        <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-wifi"></i> WiFi</span>
                                    @endif
                                    @if($roomType->has_tv)
                                        <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-tv"></i> TV</span>
                                    @endif
                                    @if($roomType->has_ac)
                                        <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-snow"></i> AC</span>
                                    @endif
                                    @if($roomType->has_breakfast)
                                        <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-cup-hot"></i> Breakfast</span>
                                    @endif
                                    @if($roomType->has_parking)
                                        <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-car-front"></i> Parking</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        {{-- CURRENT GUEST --}}
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-danger bg-opacity-10 text-danger">
                                    <h6 class="mb-0"><i class="bi bi-person-fill"></i> CURRENT GUEST</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">{{ $currentCheckin->guest->name ?? 'Guest' }}</h5>
                                            <p class="text-muted small mb-1">
                                                {{ $currentCheckin->guest->phone ?? 'No phone' }} | 
                                                {{ $currentCheckin->guest->email ?? 'No email' }}
                                            </p>
                                        </div>
                                        <span class="badge bg-danger">Occupied</span>
                                    </div>
                                    <div class="row small mb-2">
                                        <div class="col-md-6">
                                            <strong>Check-in:</strong> 
                                            {{ \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d, Y H:i') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Check-out:</strong> 
                                            {{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-dark">
                                            {{ $currentCheckin->number_of_guest }} guest(s)
                                        </span>
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#checkinDetailModal{{ $currentCheckin->id }}"
                                                data-bs-dismiss="modal"
                                            >
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <form action="{{ route('checkins.update', $currentCheckin->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_checkout" value="1">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-box-arrow-right"></i> Check Out
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- <div class="alert alert-warning mt-2 mb-0 py-2 small">
                                        This room is currently occupied and can only be checked-in again after <b>{{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') }}</b>.
                                    </div> --}}
                                </div>
                            </div>
                        @endif

                        {{-- Show bill/folio after checkout --}}
                        @if($currentCheckin && $currentCheckin->is_checkout && $currentCheckin->folio)
                            <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-receipt fs-5"></i>
                                Bill generated:
                                <a href="{{ route('frontdesk.folios.show', $currentCheckin->folio->folio_code ?? $currentCheckin->folio->id) }}" class="btn btn-sm btn-primary ms-2" target="_blank">
                                    <i class="bi bi-eye"></i> View Bill
                                </a>
                            </div>
                        @endif

                        {{-- NEXT RESERVATION --}}
                        @if($nextReservation)
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary bg-opacity-10 text-primary">
                                    <h6 class="mb-0"><i class="bi bi-calendar-check"></i> NEXT RESERVATION</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">{{ $nextReservation->guest->name ?? 'Guest' }}</h5>
                                            <p class="text-muted small mb-1">
                                                {{ $nextReservation->guest->phone ?? 'No phone' }} | 
                                                {{ $nextReservation->guest->email ?? 'No email' }}
                                            </p>
                                        </div>
                                        <span class="badge bg-primary">{{ ucfirst($nextReservation->status) }}</span>
                                    </div>
                                    <div class="row small mb-2">
                                        <div class="col-md-6">
                                            <strong>Check-in:</strong> 
                                            {{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d, Y H:i') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Check-out:</strong> 
                                            {{ \Carbon\Carbon::parse($nextReservation->checkout_date)->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-dark">
                                            {{ $nextReservation->number_of_guest }} guest(s)
                                        </span>
                                        <div class="btn-group">
                                            <button 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reservationDetailModal{{ $nextReservation->id }}"
                                                data-bs-dismiss="modal"
                                            >
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            {{-- Check-in button: disabled if occupied, enabled if not --}}
                                            @php
                                                $occupied = ($currentCheckin && !$currentCheckin->is_checkout);
                                            @endphp
                                            @if(in_array(strtolower($nextReservation->status), ['confirmed', 'pending']))
                                                @if(!$occupied)
                                                    <form action="{{ route('checkins.store') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="reservation_id" value="{{ $nextReservation->id }}">
                                                        <input type="hidden" name="redirect_to" value="front-desk">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-person-check"></i> Check In
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled title="Room is occupied. Check-in available after {{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') }}">
                                                        <i class="bi bi-person-check"></i> Check In (Occupied)
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    @if($occupied)
                                        <div class="alert alert-warning mt-2 mb-0 py-2 small">
                                            Room is currently occupied. Next check-in allowed after <b>{{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d, Y H:i') }}</b>.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- AVAILABLE & NO CURRENT/NEXT RESERVATION: Show quick action card --}}
                        @php
                            $isAvailable = strtolower($room->status) === 'available';
                            $hasNoCheckin = !$currentCheckin || ($currentCheckin && $currentCheckin->is_checkout);
                            $hasNoReservation = !$nextReservation;
                        @endphp
                        @if($isAvailable && $hasNoCheckin && $hasNoReservation)
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success bg-opacity-10 text-success">
                                    <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> ROOM AVAILABLE</h6>
                                </div>
                                <div class="card-body">
                                    <p>
                                        This room is currently available. You can:
                                    </p>
                                    <ul class="mb-0">
                                        <li>Check in a walk-in guest immediately</li>
                                        <li>Or make a new reservation for a future date</li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Cleaning/Maintenance Notice --}}
                        @if(in_array(strtolower($room->status), ['cleaning', 'maintenance']))
                            <div class="card border-warning">
                                <div class="card-header bg-warning bg-opacity-10 text-warning">
                                    <h6 class="mb-0"><i class="bi bi-tools"></i> ROOM MAINTENANCE</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('rooms.update-status', $room->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Available">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle"></i> Mark as Available
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- FOOTER BUTTONS --}}
            <div class="modal-footer d-flex flex-wrap gap-2 justify-content-end">
                {{-- Walk-in Check-in (only if available) --}}
                @php $isAvailable = strtolower($room->status) === 'available'; @endphp
                <button
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#quickCheckinModal"
                    data-room-code="{{ $room->room_code }}"
                    data-bs-dismiss="modal"
                    {{ $isAvailable ? '' : 'disabled title=Only available for rooms with status=Available' }}
                >
                    <i class="bi bi-person-plus"></i> Walk-in Check-in
                </button>
                {{-- Reservation always enabled --}}
                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#newReservationModal"
                    data-room-code="{{ $room->room_code }}"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-calendar-plus"></i> New Reservation
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
