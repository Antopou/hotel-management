@extends('layouts.main')

@section('title', 'Front Desk - Hotel Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Front Desk</li>
@endsection

@section('content')
<style>
    /* Enhanced Front Desk Styling - Smaller Fonts */
    body {
        font-size: 14px; /* Reduced from 16px */
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 0.8rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 6px 24px rgba(102, 126, 234, 0.25);
    }
    .page-title {
        font-size: 1.8rem; /* Reduced from 2.5rem */
        font-weight: 700;
        margin-bottom: 0.3rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .page-subtitle {
        font-size: 1rem; /* Reduced from 1.2rem */
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    /* Enhanced Cards - Smaller */
    .gradient-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    .gradient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 36px rgba(0,0,0,0.12);
    }
    .gradient-card .card-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .gradient-card .icon-wrapper {
        background: rgba(255,255,255,0.2);
        border-radius: 0.8rem;
        padding: 1rem;
        backdrop-filter: blur(10px);
    }
    .gradient-card .icon-wrapper i {
        font-size: 2rem; /* Reduced from 2.5rem */
        color: white;
    }
    .gradient-card .card-subtitle {
        font-size: 0.85rem; /* Reduced from 1rem */
        color: rgba(255,255,255,0.8);
        margin-bottom: 0.8rem;
        font-weight: 500;
    }
    .gradient-card .btn {
        font-size: 0.85rem; /* Reduced from 1rem */
        padding: 0.6rem 1.2rem;
        border-radius: 0.6rem;
        font-weight: 600;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .gradient-card .btn:hover {
        background: white !important;
        color: #333 !important;
        border-color: white;
        transform: scale(1.02);
    }
    
    /* Enhanced Overview Cards - Smaller */
    .overview-card {
        background: white;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 3px 16px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .overview-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .overview-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 1rem 1rem 0 0;
        padding: 1.2rem;
    }
    .overview-card .card-title {
        font-size: 1.1rem; /* Reduced from 1.3rem */
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }
    .overview-card .card-body {
        padding: 1.5rem;
    }
    
    /* Enhanced Stats - Smaller */
    .stat-number {
        font-size: 2.2rem; /* Reduced from 3rem */
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.3rem;
    }
    .stat-label {
        font-size: 0.9rem; /* Reduced from 1.1rem */
        color: #6c757d;
        font-weight: 500;
    }
    
    /* Enhanced Tables - Smaller */
    .table {
        font-size: 0.85rem; /* Reduced from 1rem */
    }
    .table th {
        font-size: 0.9rem; /* Reduced from 1rem */
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #dee2e6;
        padding: 0.8rem;
    }
    .table td {
        padding: 0.8rem;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Enhanced Badges - Smaller */
    .badge {
        font-size: 0.75rem; /* Reduced from 0.9rem */
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
        font-weight: 600;
    }
    
    /* Enhanced Avatar - Smaller */
    .avatar-sm {
        width: 2.5rem; /* Reduced from 3rem */
        height: 2.5rem;
        font-size: 1rem; /* Reduced from 1.2rem */
    }
    
    /* Better spacing - Smaller */
    .mb-4 { margin-bottom: 1.5rem !important; }
    .mb-3 { margin-bottom: 1rem !important; }
    .py-3 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
</style>

<div class="page-header">
    <h1 class="page-title">Front Desk Operations</h1>
    <p class="page-subtitle">Manage daily hotel operations and guest services</p>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card gradient-card text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle">Quick Check-in</h6>
                    <a href="{{ route('checkins.index') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i>New Check-in
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card gradient-card text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="bi bi-calendar-plus"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle">New Reservation</h6>
                    <a href="{{ route('reservations.index') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i>Book Room
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card gradient-card text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle">Guest Registration</h6>
                    <a href="{{ route('guests.index') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i>Add Guest
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card gradient-card text-white h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="icon-wrapper">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle">Billing & Folios</h6>
                    <a href="{{ route('folios.index') }}" class="btn btn-light">
                        <i class="bi bi-eye me-1"></i>View Folios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Overview -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card overview-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Today's Activity</h5>
                    <span class="badge bg-primary">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="stat-number text-primary">{{ $todayCheckins ?? 0 }}</div>
                            <div class="stat-label">Check-ins</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="stat-number text-success">{{ $todayCheckouts ?? 0 }}</div>
                            <div class="stat-label">Check-outs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="stat-number text-info">{{ $todayReservations ?? 0 }}</div>
                            <div class="stat-label">New Reservations</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="stat-number text-warning">{{ $occupancyRate ?? 0 }}%</div>
                            <div class="stat-label">Occupancy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card overview-card">
            <div class="card-header">
                <h5 class="card-title">Room Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Available</span>
                    <span class="badge bg-success">{{ $availableRooms ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Occupied</span>
                    <span class="badge bg-danger">{{ $occupiedRooms ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Out of Order</span>
                    <span class="badge bg-warning">{{ $maintenanceRooms ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total Rooms</span>
                    <span class="badge bg-info">{{ $totalRooms ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Arrivals & Departures -->
<div class="row g-4">
    <div class="col-xl-6">
        <div class="card overview-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Today's Arrivals</h5>
                    <span class="badge bg-primary">{{ count($todayArrivals ?? []) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($todayArrivals ?? [] as $arrival)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $arrival->guest->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $arrival->guest->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $arrival->room->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $arrival->checkin_date ? \Carbon\Carbon::parse($arrival->checkin_date)->format('H:i') : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $arrival->is_checkedin ? 'bg-success' : 'bg-warning' }}">
                                        {{ $arrival->is_checkedin ? 'Checked In' : 'Expected' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-x"></i>
                                        No arrivals scheduled for today
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card overview-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Today's Departures</h5>
                    <span class="badge bg-secondary">{{ count($todayDepartures ?? []) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($todayDepartures ?? [] as $departure)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $departure->guest->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $departure->guest->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $departure->room->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $departure->checkout_date ? \Carbon\Carbon::parse($departure->checkout_date)->format('H:i') : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $departure->is_checkout ? 'bg-success' : 'bg-warning' }}">
                                        {{ $departure->is_checkout ? 'Checked Out' : 'Expected' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-x"></i>
                                        No departures scheduled for today
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh page every 5 minutes to keep data current
setInterval(function() {
    location.reload();
}, 300000);
</script>
@endpush
