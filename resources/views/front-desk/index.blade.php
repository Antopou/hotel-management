@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Room Explorer (Front Desk)</h3>
    </div>
    
    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('front-desk.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="name" class="form-control" placeholder="Search by room name" value="{{ request('name') }}">
        </div>
        <div class="col-md-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="floorFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Filter by Floor
                </button>
                <ul class="dropdown-menu" aria-labelledby="floorFilterDropdown">
                    <li><a class="dropdown-item" href="#" data-floor="all">All Floors</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-floor="1">Floor 1</a></li>
                    <li><a class="dropdown-item" href="#" data-floor="2">Floor 2</a></li>
                    <li><a class="dropdown-item" href="#" data-floor="3">Floor 3</a></li>
                    <!-- Add more floors as needed -->
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <!-- End Search and Filter Form -->

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
        @foreach ($rooms as $room)
            @php
                $roomType = $room->roomType;
                $roomTypeName = $roomType->name ?? 'N/A';
                $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
                // Extract floor number from room name (assuming format like "Room 101" where 1 is floor)
                $floorNumber = substr($room->name, -3, 1); // Gets the first digit of the 3-digit room number
            @endphp
            <div class="col room-card" data-status="{{ strtolower($room->status) }}" data-floor="{{ $floorNumber }}">
                <div
                    class="card h-100 shadow-sm border-0 card-hover"
                    style="cursor:pointer"
                    data-bs-toggle="modal"
                    data-bs-target="#frontDeskRoomModal{{ $room->id }}"
                >
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $roomTypeName }}" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary mb-1">{{ $room->name }}</h5>
                        <p class="card-text text-muted small mb-1">
                            <strong>Room Type:</strong> {{ $roomTypeName }}
                        </p>
                        <p class="card-text small">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $room->status_color }}">
                                {{ $room->status }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Modal --}}
            <div class="modal fade" id="frontDeskRoomModal{{ $room->id }}" tabindex="-1" aria-labelledby="frontDeskRoomLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="frontDeskRoomLabel{{ $room->id }}">Room {{ $room->name }} Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <img src="{{ $imageUrl }}" alt="{{ $roomTypeName }}" class="img-fluid rounded shadow-sm">
                                </div>
                                <div class="col-md-8">
                                    <dl class="row">
                                        <dt class="col-sm-5 text-muted">Room Type:</dt>
                                        <dd class="col-sm-7 fw-bold">{{ $roomTypeName }}</dd>
                                        <dt class="col-sm-5 text-muted">Current Status:</dt>
                                        <dd class="col-sm-7">
                                            <span class="badge bg-{{ $room->status_color }}">
                                                {{ $room->status }}
                                            </span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <h6>Current/Upcoming Reservations</h6>
                                <ul class="list-group mb-3">
                                    @forelse ($room->reservations as $reservation)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <b>{{ $reservation->guest->name ?? 'No Guest' }}</b>
                                                <span class="small text-muted">
                                                    ({{ $reservation->checkin_date->format('M d') }} to {{ $reservation->checkout_date->format('M d') }})
                                                </span>
                                                <span class="badge bg-primary">{{ ucfirst($reservation->status) }}</span>
                                            </span>
                                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">No reservations for this room.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="mb-2">
                                <h6>Check-ins (Current & Recent)</h6>
                                <ul class="list-group mb-3">
                                    @forelse ($room->checkins as $checkin)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <b>{{ $checkin->guest->name ?? 'No Guest' }}</b>
                                                <span class="small text-muted">
                                                    ({{ $checkin->checkin_date->format('M d H:i') }} to {{ $checkin->checkout_date ? $checkin->checkout_date->format('M d H:i') : '-' }})
                                                </span>
                                                <span class="badge bg-{{ $checkin->is_checkout ? 'secondary' : 'success' }}">
                                                    {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                                                </span>
                                            </span>
                                            <a href="{{ route('checkins.show', $checkin->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">No check-in records for this room.</li>
                                    @endforelse
                                </ul>
                            </div>

                            {{-- Only show "Check-in" button if available --}}
                            @if(strtolower($room->status) === 'available')
                                <form action="{{ route('checkins.store') }}" method="POST" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="room_code" value="{{ $room->room_code }}">
                                    <div class="row">
                                        <div class="col">
                                            <select name="guest_code" class="form-select" required>
                                                <option value="">-- Select Guest --</option>
                                                @foreach ($guests as $guest)
                                                    <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="datetime-local" name="checkin_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                        </div>
                                        <div class="col">
                                            <input type="number" name="number_of_guest" class="form-control" min="1" value="1" required>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-success" type="submit">
                                                <i class="bi bi-person-check"></i> Walk-in Check-in
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <form action="{{ route('reservations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="room_code" value="{{ $room->room_code }}">
                                    <div class="row">
                                        <div class="col">
                                            <select name="guest_code" class="form-select" required>
                                                <option value="">-- Select Guest --</option>
                                                @foreach ($guests as $guest)
                                                    <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="datetime-local" name="checkin_date" class="form-control" required>
                                        </div>
                                        <div class="col">
                                            <input type="datetime-local" name="checkout_date" class="form-control" required>
                                        </div>
                                        <div class="col">
                                            <input type="number" name="number_of_guest" class="form-control" min="1" value="1" required>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="bi bi-calendar-plus"></i> New Reservation
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            {{-- If Occupied, show who's in and option to check out --}}
                            @if(strtolower($room->status) === 'occupied')
                                @php
                                    $currentCheckin = $room->checkins->where('is_checkout', false)->first();
                                @endphp
                                @if($currentCheckin)
                                    <form action="{{ route('checkins.update', $currentCheckin->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_checkout" value="1">
                                        <input type="hidden" name="guest_code" value="{{ $currentCheckin->guest_code }}">
                                        <input type="hidden" name="room_code" value="{{ $room->room_code }}">
                                        <input type="hidden" name="checkin_date" value="{{ $currentCheckin->checkin_date }}">
                                        <input type="hidden" name="checkout_date" value="{{ now()->format('Y-m-d H:i:s') }}">
                                        <input type="hidden" name="number_of_guest" value="{{ $currentCheckin->number_of_guest }}">
                                        <button class="btn btn-danger" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Check Out {{ $currentCheckin->guest->name ?? '' }}
                                        </button>
                                    </form>
                                @endif
                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<script>
// Extracts the floor number from a room name like "Room 201"
function getFloorFromRoomName(roomName) {
    // Matches the first digit after "Room "
    const match = roomName.match(/\b(\d)\d{2}\b/);
    return match ? match[1] : null;
}

// Filters rooms by floor
function filterRoomsByFloor(rooms, floor) {
    if (floor === "all") return rooms;
    return rooms.filter(room => getFloorFromRoomName(room.name) === String(floor));
}

// Add event listeners for floor filter dropdown
document.querySelectorAll('.dropdown-menu [data-floor]').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const selectedFloor = this.getAttribute('data-floor');
        // 'rooms' should be your array of room objects
        const filteredRooms = filterRoomsByFloor(rooms, selectedFloor);
        renderRooms(filteredRooms);
    });
});
</script>

<script>
$(document).ready(function() {
    // Floor filtering
    $('.dropdown-menu [data-floor]').click(function(e) {
        e.preventDefault();
        const floor = $(this).data('floor');
        
        // Update dropdown button text (keep icon/caret)
        $('#floorFilterDropdown').html('<i class="bi bi-building"></i> ' + $(this).text());
        
        if(floor === 'all') {
            $('.room-card').show();
        } else {
            $('.room-card').hide();
            $(`.room-card[data-floor="${floor}"]`).show();
        }
    });
});
</script>
