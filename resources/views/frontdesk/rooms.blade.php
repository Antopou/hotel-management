@extends('layouts.main-nosidebar')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Front Desk Dashboard</h2>
            <p class="text-muted mb-0">Manage rooms, check-ins, and reservations in real time</p>
        </div>
        <div class="d-flex">
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
                    <!-- Add more floors as needed -->
                </ul>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
                <i class="bi bi-plus-circle"></i> Quick Check-in
            </button>
        </div>
    </div>

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

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4" id="roomGrid">
        @foreach ($rooms as $room)
            @php
                $roomType = $room->roomType;
                $roomTypeName = $roomType->name ?? 'N/A';
                $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
                $currentCheckin = $room->currentCheckin();
                $nextReservation = $room->nextReservation();
                
                // Status colors
                $statusColors = [
                    'available' => 'success',
                    'occupied' => 'danger',
                    'cleaning' => 'warning',
                    'maintenance' => 'secondary'
                ];
                $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';

                // Extract floor number from room name (e.g., "Room 101" => 1)
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
                                                        {{-- Check-in button for reservation --}}
                                                        @if(in_array(strtolower($nextReservation->status), ['confirmed', 'pending']))
                                                            <form action="{{ route('checkins.store') }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="reservation_id" value="{{ $nextReservation->id }}">
                                                                <input type="hidden" name="redirect_to" value="front-desk">
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="bi bi-person-check"></i> Check In
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(strtolower($room->status) === 'available')
                                        <div class="card border-success">
                                            <div class="card-header bg-success bg-opacity-10 text-success">
                                                <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> QUICK ACTIONS</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-grid gap-2">
                                                    <button 
                                                        class="btn btn-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#quickCheckinModal"
                                                        data-room-code="{{ $room->room_code }}"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        <i class="bi bi-person-plus"></i> Walk-in Check-in
                                                    </button>
                                                    
                                                    <button 
                                                        class="btn btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#newReservationModal"
                                                        data-room-code="{{ $room->room_code }}"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        <i class="bi bi-calendar-plus"></i> New Reservation
                                                    </button>
                                                    
                                                <!-- FIXED BUTTON: Mark for Cleaning -->
                                                <form action="{{ route('rooms.update-status', $room->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Cleaning">
                                                    <button type="submit" class="btn btn-warning w-100">
                                                        <i class="bi bi-bucket"></i> Mark for Cleaning
                                                    </button>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('frontdesk._modal_checkin', ['rooms' => $rooms, 'guests' => $guests])
@include('frontdesk._modal_reservation', ['rooms' => $rooms, 'guests' => $guests])

@foreach($rooms as $room)
    @if($room->currentCheckin())
        @include('checkins._modals', ['checkin' => $room->currentCheckin()])
        {{-- Add check-in detail modal for current check-in --}}
        @include('frontdesk._modal_checkin_detail', ['checkin' => $room->currentCheckin()])
    @endif
    @if($room->nextReservation())
        @include('reservations._modals', ['reservation' => $room->nextReservation()])
        @include('frontdesk._modal_reservation_detail', ['nextReservation' => $room->nextReservation()])
    @endif
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFloorFilter = 'all';
    let currentStatusFilter = 'all';

    // Set default filter button text (no icon)
    $('#filterDropdown').text('All Rooms');
    $('#floorFilterDropdown').text('All Floors');

    // Floor filter
    $('.dropdown-menu [data-floor]').click(function(e) {
        e.preventDefault();
        currentFloorFilter = $(this).data('floor');
        $('#floorFilterDropdown').text($(this).text());
        applyFilters();
    });

    // Status filter
    $('.dropdown-menu [data-filter]').click(function(e) {
        e.preventDefault();
        currentStatusFilter = $(this).data('filter');
        $('#filterDropdown').text($(this).text());
        applyFilters();
    });

    // DO NOT call applyFilters or update dropdowns anywhere else!

    function applyFilters() {
        $('.room-card').show();

        if(currentFloorFilter !== 'all') {
            $('.room-card').not(`[data-floor="${currentFloorFilter}"]`).hide();
        }
        if(currentStatusFilter !== 'all') {
            $('.room-card').not(`[data-status="${currentStatusFilter}"]`).hide();
        }
    }

    // Room card click or modal open does not touch any filter!
});
</script>
@endpush