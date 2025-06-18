@extends('layouts.main')

@section('title', 'Front Desk - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Front Desk</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Front Desk Operations</h1>
    <p class="page-subtitle">Manage daily hotel operations and guest services</p>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-box-arrow-in-right fs-2"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle mb-2 text-white-50">Quick Check-in</h6>
                    <a href="{{ route('checkins.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>New Check-in
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-calendar-plus fs-2"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle mb-2 text-white-50">New Reservation</h6>
                    <a href="{{ route('reservations.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Book Room
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-person-plus fs-2"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle mb-2 text-white-50">Guest Registration</h6>
                    <a href="{{ route('guests.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add Guest
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-receipt fs-2"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="card-subtitle mb-2 text-white-50">Billing & Folios</h6>
                    <a href="{{ route('folios.index') }}" class="btn btn-light btn-sm">
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Today's Activity</h5>
                    <span class="badge bg-primary">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold text-primary">{{ $todayCheckins ?? 0 }}</div>
                            <div class="text-muted">Check-ins</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold text-success">{{ $todayCheckouts ?? 0 }}</div>
                            <div class="text-muted">Check-outs</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold text-info">{{ $todayReservations ?? 0 }}</div>
                            <div class="text-muted">New Reservations</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="display-6 fw-bold text-warning">{{ $occupancyRate ?? 0 }}%</div>
                            <div class="text-muted">Occupancy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Room Status</h5>
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Today's Arrivals</h5>
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
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Today's Departures</h5>
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
