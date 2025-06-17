@extends('layouts.main-nosidebar')

@section('content')
{{-- Fullscreen Loader --}}
<style>
    #fullscreen-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        transition: opacity 0.5s;
    }
    #fullscreen-loader.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #0d6efd;
    }
</style>
<div id="fullscreen-loader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
{{-- End Loader --}}

<div class="container-fluid py-4">
    <style>
        /* Clean, modern cards */
        .dashboard-card {
            border: none;
            box-shadow: 0 2px 12px rgba(60,60,60,0.08);
            border-radius: 1rem;
            transition: box-shadow 0.2s;
        }
        .dashboard-card .icon {
            font-size: 2.5rem;
            opacity: 0.9;
        }
        .dashboard-card h3 {
            font-weight: 700;
            margin-bottom: 0;
        }
        .dashboard-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dashboard-card .desc {
            font-size: .96rem;
            color: #888;
            letter-spacing: .03em;
        }
        .filter-form .form-select, .filter-form .btn {
            min-width: 110px;
            border-radius: 1.5rem;
        }
        .toggle-btns .btn {
            border-radius: 50px;
            font-size: 1.15rem;
            padding: 0.4rem 1rem;
        }
        .toggle-btns .btn.active {
            background: #edf2fa;
            border-color: #0d6efd;
            color: #0d6efd;
        }
        /* Room card clean-up */
        .room-card .card {
            border-radius: 1rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 2px 8px rgba(30,50,90,0.06);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .room-card .card:hover {
            box-shadow: 0 8px 32px rgba(30,50,90,0.13);
            transform: translateY(-4px) scale(1.02);
        }
        .room-card .card-img-top {
            border-radius: 0.7rem 0.7rem 0 0;
        }
        .room-card .badge {
            font-size: 0.91rem;
        }
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background: #f7fafc;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="letter-spacing: -.01em;">Front Desk Dashboard</h2>
            <div class="desc mb-0">Manage rooms, check-ins, and reservations in real time</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" action="{{ route('front-desk.index') }}" class="d-flex align-items-center gap-2 mb-0 filter-form">
                <select name="floor" class="form-select">
                    <option value="all" {{ request('floor', 'all') == 'all' ? 'selected' : '' }}>All Floors</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>Floor {{ $floor }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-select">
                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button type="submit" class="btn btn-primary px-4">Filter</button>
            </form>
            <div class="btn-group ms-2 toggle-btns" role="group">
                <button class="btn btn-outline-secondary active" id="btnCardView" title="Card view">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="btn btn-outline-secondary" id="btnTableView" title="Table view">
                    <i class="bi bi-table"></i>
                </button>
            </div>
            <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
                <i class="bi bi-plus-circle"></i> Quick Check-in
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-5 g-4">
        @php
            $statusColors = [
                'available' => 'success',
                'occupied' => 'danger',
                'cleaning' => 'warning',
                'maintenance' => 'secondary'
            ];
        @endphp
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-1">Total Rooms</div>
                        <h3>{{ $rooms->total() ?? $rooms->count() }}</h3>
                    </div>
                    <div class="icon text-primary bg-primary bg-opacity-10 rounded p-3">
                        <i class="bi bi-door-closed"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-1">Available</div>
                        <h3>{{ $rooms->where('status', 'available')->count() }}</h3>
                    </div>
                    <div class="icon text-success bg-success bg-opacity-10 rounded p-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-1">Occupied</div>
                        <h3>{{ $rooms->where('status', 'occupied')->count() }}</h3>
                    </div>
                    <div class="icon text-danger bg-danger bg-opacity-10 rounded p-3">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-1">Require Attention</div>
                        <h3>{{ $rooms->whereIn('status', ['cleaning', 'maintenance'])->count() }}</h3>
                    </div>
                    <div class="icon text-warning bg-warning bg-opacity-10 rounded p-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card View --}}
    <div class="row g-4" id="cardView">
        @foreach ($rooms as $room)
            @php
                $roomType = $room->roomType;
                $roomTypeName = $roomType->name ?? 'N/A';
                $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
                $currentCheckin = $room->currentCheckIn();
                $nextReservation = $room->nextReservation();
                $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
                $floorNumber = substr($room->name, -3, 1);
            @endphp
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 room-card" data-status="{{ strtolower($room->status) }}" data-floor="{{ $floorNumber }}">
                <div class="card h-100 clickable-card" data-bs-toggle="modal" data-bs-target="#roomDetailModal{{ $room->id }}" style="cursor: pointer;">
                    <div class="position-relative">
                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $roomTypeName }}" style="height: 170px; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $statusColor }} text-uppercase px-3 py-2" style="font-size:0.95rem; box-shadow: 0 2px 4px rgba(0,0,0,0.08);">
                            {{ ucfirst($room->status) }}
                        </span>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark px-2 py-1">
                                <i class="bi bi-person-fill"></i> {{ $currentCheckin->guest->name ?? 'Guest' }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body pb-2">
                        <h5 class="card-title mb-1">{{ $room->name }}</h5>
                        <div class="desc mb-2">{{ $roomTypeName }}</div>
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
                        @if($nextReservation && in_array(strtolower($room->status), ['available', 'reserved']))
                            <div class="alert alert-info py-1 px-2 small mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <i class="bi bi-calendar-check"></i> Next reservation
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
                        <span class="badge bg-light text-dark text-monospace px-2 py-1 mb-1">
                            {{ $room->room_code }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table View --}}
    <div id="tableView" style="display:none;">
        <table class="table table-hover align-middle bg-white rounded shadow-sm">
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
                    $currentCheckin = $room->currentCheckIn();
                    $nextReservation = $room->nextReservation();
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
                        @elseif($nextReservation && in_array(strtolower($room->status), ['available', 'reserved']))
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
        <div>
            {{ $rooms->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Render all room detail modals --}}
    @foreach($rooms as $room)
        @php
            $roomType = $room->roomType;
            $roomTypeName = $roomType->name ?? 'N/A';
            $imageUrl = $roomType && $roomType->image ? asset('storage/' . $roomType->image) : asset('images/room_types/default.jpg');
            $currentCheckin = $room->currentCheckIn();
            $nextReservation = $room->nextReservation();
            $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
        @endphp
        @include('front-desk._modal_room_detail', [
            'room' => $room,
            'roomType' => $roomType,
            'roomTypeName' => $roomTypeName,
            'imageUrl' => $imageUrl,
            'currentCheckin' => $currentCheckin,
            'nextReservation' => $nextReservation,
            'statusColor' => $statusColor
        ])
    @endforeach

    @include('front-desk._modal_checkin', ['rooms' => $rooms, 'guests' => $guests])
    @include('front-desk._modal_reservation', ['rooms' => $rooms, 'guests' => $guests])
    @foreach($rooms as $room)
        @if($room->currentCheckin())
            @include('checkins._modals', ['checkin' => $room->currentCheckin()])
            @include('front-desk._modal_checkin_detail', ['checkin' => $room->currentCheckin()])
        @endif
        @if($room->nextReservation())
            @include('reservations._modals', ['reservation' => $room->nextReservation()])
            @include('front-desk._modal_reservation_detail', ['nextReservation' => $room->nextReservation()])
        @endif
    @endforeach

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('fullscreen-loader');
    loader.classList.add('hidden');

    document.querySelectorAll('a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && !href.startsWith('http') && !href.startsWith('#') && !link.hasAttribute('target')) {
            link.addEventListener('click', e => {
                e.preventDefault();
                loader.classList.remove('hidden');
                setTimeout(() => window.location.href = href, 200);
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    let currentFloorFilter = 'all';
    let currentStatusFilter = 'all';

    // Restore view from localStorage
    let savedView = localStorage.getItem('roomView') || 'card';
    if (savedView === 'table') {
        $('#cardView').hide();
        $('#tableView').show();
        $('#btnTableView').addClass('active');
        $('#btnCardView').removeClass('active');
    } else {
        $('#tableView').hide();
        $('#cardView').show();
        $('#btnCardView').addClass('active');
        $('#btnTableView').removeClass('active');
    }

    // View switch
    $('#btnCardView').click(function() {
        $('#tableView').hide();
        $('#cardView').show();
        $(this).addClass('active');
        $('#btnTableView').removeClass('active');
        localStorage.setItem('roomView', 'card');
    });
    $('#btnTableView').click(function() {
        $('#cardView').hide();
        $('#tableView').show();
        $(this).addClass('active');
        $('#btnCardView').removeClass('active');
        localStorage.setItem('roomView', 'table');
    });
});
</script>
@endpush
