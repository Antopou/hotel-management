@extends('layouts.main-nosidebar')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Front Desk Dashboard</h2>
            <p class="text-muted mb-0">Manage rooms, check-ins, and reservations in real time</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item" href="#" data-filter="all">All Rooms</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-filter="available">Available</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="occupied">Occupied</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="cleaning">Cleaning</a></li>
                    <li><a class="dropdown-item" href="#" data-filter="maintenance">Maintenance</a></li>
                </ul>
            </div>
            <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="floorFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-building"></i> Floor
                </button>
                <ul class="dropdown-menu" aria-labelledby="floorFilterDropdown">
                    <li><a class="dropdown-item" href="#" data-floor="all">All Floors</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-floor="1">Floor 1</a></li>
                    <li><a class="dropdown-item" href="#" data-floor="2">Floor 2</a></li>
                    <li><a class="dropdown-item" href="#" data-floor="3">Floor 3</a></li>
                </ul>
            </div>
            <div class="btn-group ms-2" role="group">
                <button class="btn btn-outline-secondary active" id="btnCardView">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="btn btn-outline-secondary" id="btnTableView">
                    <i class="bi bi-table"></i>
                </button>
            </div>
            <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
                <i class="bi bi-plus-circle"></i> Quick Check-in
            </button>
        </div>
    </div>

    {{-- Dashboard summary cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Total Rooms</h6>
                            <h3 class="mb-0">{{ $rooms->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-door-closed text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Available</h6>
                            <h3 class="mb-0">{{ $rooms->where('status', 'available')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Occupied</h6>
                            <h3 class="mb-0">{{ $rooms->where('status', 'occupied')->count() }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-fill text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Require Attention</h6>
                            <h3 class="mb-0">{{ $rooms->whereIn('status', ['cleaning', 'maintenance'])->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card View --}}
    <div class="row" id="cardView">
        @foreach ($rooms as $room)
            @php
                $roomType = $room->roomType;
                $roomTypeName = $roomType->name ?? 'N/A';
                $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
                $currentCheckin = $room->currentCheckin();
                $nextReservation = $room->nextReservation();
                $statusColors = [
                    'available' => 'success',
                    'occupied' => 'danger',
                    'cleaning' => 'warning',
                    'maintenance' => 'secondary'
                ];
                $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
                $floorNumber = substr($room->name, -3, 1);
            @endphp

            <div class="col room-card" data-status="{{ strtolower($room->status) }}" data-floor="{{ $floorNumber }}">
                <div 
                    class="card h-100 shadow-sm border-0 overflow-hidden room-card-inner clickable-card"
                    data-bs-toggle="modal"
                    data-bs-target="#roomDetailModal{{ $room->id }}"
                    style="cursor: pointer;"
                >
                    <div class="position-relative">
                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $roomTypeName }}" style="height: 180px; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $statusColor }} text-uppercase">
                            {{ $room->status }}
                        </span>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark">
                                <i class="bi bi-person-fill"></i> {{ $currentCheckin->guest->name ?? 'Guest' }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">
                                {{ $room->name }}
                                <small class="text-muted d-block">{{ $roomTypeName }}</small>
                            </h5>
                            <span class="badge bg-light text-dark">
                                {{ $room->room_code }}
                            </span>
                        </div>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <div class="alert alert-danger py-1 px-2 small mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <i class="bi bi-person-fill"></i> 
                                        {{ $currentCheckin->guest->name ?? 'Guest' }}
                                    </span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if($nextReservation && strtolower($room->status) === 'available')
                            <div class="alert alert-info py-1 px-2 small mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <i class="bi bi-calendar-check"></i> 
                                        Next reservation
                                    </span>
                                    <span>
                                        {{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            @if($roomType)
                                @if($roomType->has_wifi)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-wifi"></i> WiFi</span>
                                @endif
                                @if($roomType->has_tv)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-tv"></i> TV</span>
                                @endif
                                @if($roomType->has_ac)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-snow"></i> AC</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- <--- REMOVED @include('frontdesk._modal_room_detail', ['room' => $room]) FROM HERE! --}}
        @endforeach
    </div>

    {{-- Table View --}}
    <div id="tableView" style="display:none;">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Room Code</th>
                    <th>Type</th>
                    <th>Floor</th>
                    <th>Status</th>
                    <th>Guest/Reservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rooms as $room)
                @php
                    $roomType = $room->roomType;
                    $roomTypeName = $roomType->name ?? 'N/A';
                    $floorNumber = substr($room->name, -3, 1);
                    $currentCheckin = $room->currentCheckin();
                    $nextReservation = $room->nextReservation();
                    $statusColors = [
                        'available' => 'success',
                        'occupied' => 'danger',
                        'cleaning' => 'warning',
                        'maintenance' => 'secondary'
                    ];
                    $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
                @endphp
                <tr data-status="{{ strtolower($room->status) }}" data-floor="{{ $floorNumber }}">
                    <td><strong>{{ $room->name }}</strong></td>
                    <td><span class="text-monospace">{{ $room->room_code }}</span></td>
                    <td>{{ $roomTypeName }}</td>
                    <td>Floor {{ $floorNumber }}</td>
                    <td>
                        <span class="badge bg-{{ $statusColor }}">{{ ucfirst($room->status) }}</span>
                    </td>
                    <td>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <span class="text-danger">
                                {{ $currentCheckin->guest->name ?? 'Guest' }}
                                (In: {{ \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d H:i') }})
                            </span>
                        @elseif($nextReservation && strtolower($room->status) === 'available')
                            <span class="text-info">
                                Reserved for {{ $nextReservation->guest->name ?? 'Guest' }} 
                                ({{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d') }})
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#roomDetailModal{{ $room->id }}">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- <<<<<<<<<<<  NEW! Render all room detail modals HERE so they're always present >>>>>>>>>> --}}
    @foreach($rooms as $room)
        @php
            $roomType = $room->roomType;
            $roomTypeName = $roomType->name ?? 'N/A';
            $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
            $currentCheckin = $room->currentCheckin();
            $nextReservation = $room->nextReservation();
            $statusColors = [
                'available' => 'success',
                'occupied' => 'danger',
                'cleaning' => 'warning',
                'maintenance' => 'secondary'
            ];
            $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
        @endphp
        @include('frontdesk._modal_room_detail', [
            'room' => $room,
            'roomType' => $roomType,
            'roomTypeName' => $roomTypeName,
            'imageUrl' => $imageUrl,
            'currentCheckin' => $currentCheckin,
            'nextReservation' => $nextReservation,
            'statusColor' => $statusColor
        ])
    @endforeach

    {{-- Other modals as before --}}
    @include('frontdesk._modal_checkin', ['rooms' => $rooms, 'guests' => $guests])
    @include('frontdesk._modal_reservation', ['rooms' => $rooms, 'guests' => $guests])
    @foreach($rooms as $room)
        @if($room->currentCheckin())
            @include('checkins._modals', ['checkin' => $room->currentCheckin()])
            @include('frontdesk._modal_checkin_detail', ['checkin' => $room->currentCheckin()])
        @endif
        @if($room->nextReservation())
            @include('reservations._modals', ['reservation' => $room->nextReservation()])
            @include('frontdesk._modal_reservation_detail', ['nextReservation' => $room->nextReservation()])
        @endif
    @endforeach

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFloorFilter = 'all';
    let currentStatusFilter = 'all';

    // Floor filter
    $('.dropdown-menu [data-floor]').click(function(e) {
        e.preventDefault();
        currentFloorFilter = $(this).data('floor');
        $('#floorFilterDropdown').html('<i class="bi bi-building"></i> ' + $(this).text());
        applyFilters();
    });

    // Status filter
    $('.dropdown-menu [data-filter]').click(function(e) {
        e.preventDefault();
        currentStatusFilter = $(this).data('filter');
        $('#filterDropdown').html('<i class="bi bi-funnel-fill"></i> ' + $(this).text());
        applyFilters();
    });

    // View switch
    $('#btnCardView').click(function() {
        $('#tableView').hide();
        $('#cardView').show();
        $(this).addClass('active');
        $('#btnTableView').removeClass('active');
    });
    $('#btnTableView').click(function() {
        $('#cardView').hide();
        $('#tableView').show();
        $(this).addClass('active');
        $('#btnCardView').removeClass('active');
    });

    function applyFilters() {
        // Card View
        $('#cardView .room-card').each(function() {
            var show = true;
            if (currentFloorFilter !== 'all' && $(this).data('floor') != currentFloorFilter) show = false;
            if (currentStatusFilter !== 'all' && $(this).data('status') != currentStatusFilter) show = false;
            $(this).toggle(show);
        });
        // Table View
        $('#tableView tbody tr').each(function() {
            var show = true;
            if (currentFloorFilter !== 'all' && $(this).data('floor') != currentFloorFilter) show = false;
            if (currentStatusFilter !== 'all' && $(this).data('status') != currentStatusFilter) show = false;
            $(this).toggle(show);
        });
    }
});
</script>
@endpush
