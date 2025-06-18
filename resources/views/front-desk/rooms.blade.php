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
        width: 4rem;
        height: 4rem;
        color: #0d6efd;
    }
    
    /* Enhanced Front Desk Rooms Styling */
    body {
        font-size: 16px;
        line-height: 1.6;
    }
    
    /* Clean, modern cards */
    .dashboard-card {
        border: none;
        box-shadow: 0 4px 20px rgba(60,60,60,0.1);
        border-radius: 1.2rem;
        transition: all 0.3s ease;
        background: white;
    }
    .dashboard-card:hover {
        box-shadow: 0 8px 32px rgba(60,60,60,0.15);
        transform: translateY(-4px);
    }
    .dashboard-card .icon {
        font-size: 3rem;
        opacity: 0.9;
    }
    .dashboard-card h3 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 2.5rem;
    }
    .dashboard-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2rem;
    }
    .dashboard-card .desc {
        font-size: 1.1rem;
        color: #666;
        letter-spacing: .02em;
        font-weight: 500;
    }
    
    /* Enhanced page header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    }
    .page-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .page-header .desc {
        font-size: 1.2rem;
        opacity: 0.9;
        color: white;
    }
    
    /* Enhanced filters and controls */
    .filter-form .form-select, .filter-form .btn {
        min-width: 130px;
        border-radius: 0.75rem;
        font-size: 1rem;
        padding: 0.75rem 1rem;
        font-weight: 500;
    }
    .toggle-btns .btn {
        border-radius: 0.75rem;
        font-size: 1.2rem;
        padding: 0.75rem 1rem;
        font-weight: 600;
    }
    .toggle-btns .btn.active {
        background: #e3f2fd;
        border-color: #0d6efd;
        color: #0d6efd;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
    }
    
    /* Enhanced room cards */
    .room-card .card {
        border-radius: 1.2rem;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 16px rgba(30,50,90,0.08);
        transition: all 0.3s ease;
        background: white;
    }
    .room-card .card:hover {
        box-shadow: 0 12px 40px rgba(30,50,90,0.15);
        transform: translateY(-6px) scale(1.02);
    }
    .room-card .card-img-top {
        border-radius: 0;
        height: 200px;
        object-fit: cover;
    }
    .room-card .badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .room-card .card-body {
        padding: 1.5rem;
    }
    .room-card .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
    }
    .room-card .desc {
        font-size: 1rem;
        color: #666;
        font-weight: 500;
    }
    .room-card .alert {
        font-size: 0.95rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
    }
    
    /* Enhanced table */
    .table {
        font-size: 1rem;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .table th {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        background: #f8f9fa;
        border: none;
        padding: 1.2rem;
    }
    .table td {
        padding: 1.2rem;
        vertical-align: middle;
        border-color: #f1f3f4;
    }
    .table-hover tbody tr:hover {
        background: #f8fafc;
    }
    
    /* Enhanced pagination */
    .pagination {
        justify-content: center;
        margin-top: 2.5rem;
    }
    .pagination .page-link {
        font-size: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        font-weight: 500;
    }
    
    /* Enhanced buttons */
    .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-sm {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
</style>
<div id="fullscreen-loader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
{{-- End Loader --}}

<div class="container-fluid py-4">
    <div class="page-header">
        <h2 class="fw-bold mb-1">🏨 Front Desk Dashboard</h2>
        <div class="desc mb-0">Manage rooms, check-ins, and reservations in real time</div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <form method="GET" action="{{ route('front-desk.index') }}" class="d-flex align-items-center gap-3 mb-0 filter-form">
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
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </form>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="btn-group toggle-btns" role="group">
                <button class="btn btn-outline-secondary active" id="btnCardView" title="Card view">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Cards
                </button>
                <button class="btn btn-outline-secondary" id="btnTableView" title="Table view">
                    <i class="bi bi-table me-2"></i>Table
                </button>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
                <i class="bi bi-plus-circle me-2"></i>Quick Check-in
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
                        <div class="desc mb-2">Total Rooms</div>
                        <h3>{{ $rooms->total() ?? $rooms->count() }}</h3>
                    </div>
                    <div class="icon text-primary bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-door-closed"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-2">Available</div>
                        <h3>{{ $rooms->where('status', 'available')->count() }}</h3>
                    </div>
                    <div class="icon text-success bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-2">Occupied</div>
                        <h3>{{ $rooms->where('status', 'occupied')->count() }}</h3>
                    </div>
                    <div class="icon text-danger bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div>
                        <div class="desc mb-2">Require Attention</div>
                        <h3>{{ $rooms->whereIn('status', ['cleaning', 'maintenance'])->count() }}</h3>
                    </div>
                    <div class="icon text-warning bg-warning bg-opacity-10 rounded-3 p-3">
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
                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $roomTypeName }}">
                        <span class="position-absolute top-0 end-0 m-3 badge bg-{{ $statusColor }} text-uppercase px-3 py-2" style="font-size:1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                            {{ ucfirst($room->status) }}
                        </span>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <span class="position-absolute bottom-0 start-0 m-3 badge bg-dark px-3 py-2" style="font-size:0.9rem;">
                                <i class="bi bi-person-fill me-1"></i>{{ $currentCheckin->guest->name ?? 'Guest' }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $room->name }}</h5>
                        <div class="desc mb-3">{{ $roomTypeName }}</div>
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <div class="alert alert-danger py-2 px-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-person-fill me-1"></i>
                                        {{ $currentCheckin->guest->name ?? 'Guest' }}
                                    </span>
                                    <small>
                                        {{ \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($currentCheckin->checkout_date)->format('M d') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                        @if($nextReservation && in_array(strtolower($room->status), ['available', 'reserved']))
                            <div class="alert alert-info py-2 px-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-calendar-check me-1"></i>Next reservation
                                    </span>
                                    <small>
                                        {{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($roomType)
                                @if($roomType->has_wifi)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-wifi me-1"></i>WiFi</span>
                                @endif
                                @if($roomType->has_tv)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-tv me-1"></i>TV</span>
                                @endif
                                @if($roomType->has_ac)
                                    <span class="badge bg-info bg-opacity-10 text-info"><i class="bi bi-snow me-1"></i>AC</span>
                                @endif
                            @endif
                        </div>
                        <span class="badge bg-light text-dark text-monospace px-3 py-2">
                            {{ $room->room_code }}
                        </span>
                    </div>
                </div>
            </div>
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
                    $currentCheckin = $room->currentCheckIn();
                    $nextReservation = $room->nextReservation();
                    $statusColor = $statusColors[strtolower($room->status)] ?? 'secondary';
                @endphp
                <tr data-status="{{ strtolower($room->status) }}" data-floor="{{ $floorNumber }}">
                    <td><strong class="fs-5">{{ $room->name }}</strong></td>
                    <td><span class="text-monospace fs-5">{{ $room->room_code }}</span></td>
                    <td class="fs-5">{{ $roomTypeName }}</td>
                    <td class="fs-5">Floor {{ $floorNumber }}</td>
                    <td>
                        <span class="badge bg-{{ $statusColor }} fs-6">{{ ucfirst($room->status) }}</span>
                    </td>
                    <td class="fs-5">
                        @if($currentCheckin && !$currentCheckin->is_checkout)
                            <span class="text-danger">
                                {{ $currentCheckin->guest->name ?? 'Guest' }}
                                <br><small>(In: {{ \Carbon\Carbon::parse($currentCheckin->checkin_date)->format('M d H:i') }})</small>
                            </span>
                        @elseif($nextReservation && in_array(strtolower($room->status), ['available', 'reserved']))
                            <span class="text-info">
                                Reserved for {{ $nextReservation->guest->name ?? 'Guest' }}
                                <br><small>({{ \Carbon\Carbon::parse($nextReservation->checkin_date)->format('M d') }})</small>
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#roomDetailModal{{ $room->id }}">
                            <i class="bi bi-eye me-1"></i>Detail
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
