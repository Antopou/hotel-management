@extends('layouts.main-nosidebar')

@section('content')
<style>
    #fullscreen-loader {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(255,255,255,0.95);
        display: flex; align-items: center; justify-content: center;
        z-index: 99999; transition: opacity 0.5s;
    }
    #fullscreen-loader.hidden { opacity: 0; pointer-events: none; }
    .spinner-border { width: 2rem; height: 2rem; color: #0d6efd; }

    body { font-size: 13px; line-height: 1.45; }

    .dashboard-card {
        border: none; box-shadow: 0 1px 6px rgba(60,60,60,0.07);
        border-radius: 0.6rem; background: white;
    }
    .dashboard-card .icon { font-size: 1.5rem; }
    .dashboard-card h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 0; }
    .dashboard-card .card-body { padding: 0.7rem 1rem; }
    .dashboard-card .desc { font-size: 0.85rem; color: #666; }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white; padding: 1.1rem 1rem; border-radius: 0.8rem;
        margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(102,126,234,0.13);
    }
    .page-header h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 0.3rem; }
    .page-header .desc { font-size: 1rem; opacity: 0.95; }

    /* Compact Filter Bar */
    .filter-bar {
        background: #fff;
        border-radius: 0.8rem;
        box-shadow: 0 1px 6px rgba(80, 80, 130, 0.08);
        padding: 0.7rem 1rem;
        margin-bottom: 1.2rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.7rem;
        /* Removed background-image and background-size for clean look */
    }
    .filter-bar .filter-form {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0 !important;
    }
    .filter-bar .form-select {
        min-width: 110px;
        font-size: 1rem;
        padding: 0.35rem 1rem 0.35rem 0.9rem;
        border-radius: 0.6rem;
        background: #f8fafc;
        box-shadow: 0 0.5px 2px rgba(30,40,70,0.03);
        border: 1.5px solid #e6e8ec;
        font-weight: 500;
        color: #222;
        height: 38px;
        line-height: 1.2;
        background-position: right 0.8rem center;
        background-size: 1em;
    }
    .filter-bar .form-select:focus {
        border-color: #347cf7;
        outline: none;
        box-shadow: 0 0 0 2px #d5e6ff;
    }
    .filter-bar .btn-primary {
        min-width: 90px;
        font-size: 1rem;
        border-radius: 0.6rem;
        padding: 0.35rem 1.2rem 0.35rem 1.2rem;
        background: #347cf7;
        border: none;
        font-weight: 600;
        box-shadow: 0 1px 4px rgba(40,80,180,0.05);
        height: 38px;
        line-height: 1.2;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .filter-bar .btn-primary:active,
    .filter-bar .btn-primary:focus {
        background: #175bc6;
    }
    .toggle-btns {
        border: 1.2px solid #b9c9e2;
        border-radius: 0.7rem;
        background: #f5f8fd;
        overflow: hidden;
        display: flex;
        align-items: center;
        height: 38px;
    }
    .toggle-btns .btn {
        font-size: 1rem;
        padding: 0.35rem 1rem;
        height: 38px;
        font-weight: 600;
        border-radius: 0;
        gap: 0.3rem;
        border: none;
        border-right: 1.2px solid #b9c9e2;
        background: none;
        color: #495468;
        transition: background .13s, color .13s;
        box-shadow: none !important;
    }
    .toggle-btns .btn.active {
        background: #e6f0fe;
        color: #2260c4;
        border-right: 1.2px solid #b9c9e2;
    }
    .toggle-btns .btn:first-child {
        border-left: none !important;
    }
    .toggle-btns .btn:last-child {
        border-right: none !important;
    }
    .btn-checkin {
        background: #438961;
        color: #fff;
        border-radius: 0.7rem;
        font-size: 1.05rem;
        font-weight: 700;
        padding: 0.35rem 1.2rem;
        border: none;
        height: 38px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 1px 4px rgba(60,130,90,0.05);
    }
    .btn-checkin i {
        font-size: 1.2em;
        margin-right: 0.4em;
    }
    .btn-checkin:hover, .btn-checkin:focus {
        background: #25683e;
        color: #fff;
    }

    /* Dashboard card improvements */
    .dashboard-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem 1.2rem;
    }
    .dashboard-card .desc {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 0.3rem;
        font-weight: 500;
        letter-spacing: 0.02em;
    }
    .dashboard-card h3 {
        font-size: 2.1rem;
        font-weight: 400; /* Remove bold */
        margin-bottom: 0.2rem;
        line-height: 1;
    }
    .dashboard-card .icon {
        font-size: 2rem;
        background: #e6edfa;
        border-radius: 0.8rem;
        padding: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        min-height: 48px;
        margin-left: 0.5rem;
    }
    .dashboard-card .icon.text-success { background: #eaf7ee; }
    .dashboard-card .icon.text-danger { background: #fdeaea; }
    .dashboard-card .icon.text-warning { background: #fff9ea; }

    /* Table view: make more compact */
    #tableView .table {
        font-size: 0.97rem;
        border-radius: 0.5rem;
    }
    #tableView .table th, #tableView .table td {
        padding: 0.55rem 0.7rem;
        vertical-align: middle;
    }
    #tableView .table th {
        font-size: 1rem;
        font-weight: 600;
    }
    #tableView .table td {
        font-size: 0.97rem;
        font-weight: 400;
    }
    #tableView .fs-5, #tableView .fs-6 {
        font-size: 1rem !important;
        font-weight: 400 !important;
    }
    #tableView .badge {
        font-size: 0.92rem;
        padding: 0.3em 0.7em;
    }
    #tableView .btn {
        font-size: 0.95rem;
        padding: 0.3rem 0.7rem;
        border-radius: 0.4rem;
    }
    #tableView .text-monospace {
        font-family: 'Fira Mono', 'Consolas', monospace;
    }
    #tableView .pagination {
        margin-top: 0.5rem;
    }

    @media (max-width: 991px) {
        .dashboard-card .card-body { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .dashboard-card h3 { font-size: 1.3rem; }
        .dashboard-card .icon { font-size: 1.3rem; min-width: 36px; min-height: 36px; padding: 0.5rem; }
    }
    @media (max-width: 600px) {
        .filter-bar { padding: 0.4rem 0.3rem; }
        .filter-bar .form-select, .filter-bar .btn-primary, .toggle-btns .btn, .btn-checkin {
            font-size: 0.95rem; height: 32px; padding: 0.2rem 0.7rem;
        }
        .toggle-btns { height: 32px; }
        .btn-checkin { font-size: 0.95rem; padding: 0.2rem 0.7rem; }
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
        <h2 class="fw-bold mb-1">
            {{-- Hotel icon using Bootstrap Icon --}}
            <i class="bi bi-building" style="font-size:2.2rem;vertical-align:-6px;margin-right:8px;"></i>
            Front Desk Dashboard
        </h2>
        <div class="desc mb-0">Manage rooms, check-ins, and reservations in real time</div>
    </div>

    {{-- MODERN FILTER BAR --}}
    <div class="filter-bar mb-4">
        <form method="GET" action="{{ route('front-desk.index') }}" class="filter-form">
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
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </form>
        <div class="toggle-btns btn-group" role="group">
            <button class="btn @if((session('roomView') ?? 'card') === 'card') active @endif" id="btnCardView" type="button">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
            <button class="btn @if((session('roomView') ?? 'card') === 'table') active @endif" id="btnTableView" type="button">
                <i class="bi bi-table"></i>
            </button>
        </div>
        <button class="btn-checkin" data-bs-toggle="modal" data-bs-target="#quickCheckinModal">
            <i class="bi bi-plus-circle"></i>Quick Check-in
        </button>
    </div>
    {{-- /filter-bar --}}

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
                    <div class="icon text-primary">
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
                    <div class="icon text-success">
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
                    <div class="icon text-danger">
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
                    <div class="icon text-warning">
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

    {{-- Render all reservation detail modals --}}
    @foreach($rooms as $room)
        @php
            $nextReservation = $room->nextReservation();
        @endphp
        @if($nextReservation)
            @include('front-desk._modal_reservation_detail', ['nextReservation' => $nextReservation])
        @endif
        @php
            $currentCheckin = $room->currentCheckIn();
        @endphp
        @if($currentCheckin)
            @include('front-desk._modal_checkin_detail', ['checkin' => $currentCheckin])
        @endif
    @endforeach

    @include('front-desk._modal_checkin')
    @include('front-desk._modal_reservation')
    @include('front-desk._modal_add_guest')

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
    // On load, restore last view
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
});
</script>
@endpush
