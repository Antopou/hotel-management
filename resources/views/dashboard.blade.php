@extends('layouts.main')

@section('title', 'Dashboard - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening at your hotel today.</p>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total Rooms</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $totalRooms ?? 0 }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            Available: {{ $availableRooms ?? 0 }}
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-door-open fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Active Guests</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $activeGuests ?? 0 }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            Check-ins today: {{ $todayCheckins ?? 0 }}
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Reservations</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ $totalReservations ?? 0 }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            Pending: {{ $pendingReservations ?? 0 }}
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-calendar-check fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Revenue Today</h6>
                        <h2 class="card-title mb-0 fw-bold">${{ number_format($todayRevenue ?? 0, 2) }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            +12% from yesterday
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-currency-dollar fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Revenue Overview</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="period" id="week" checked>
                        <label class="btn btn-outline-primary" for="week">Week</label>
                        <input type="radio" class="btn-check" name="period" id="month">
                        <label class="btn btn-outline-primary" for="month">Month</label>
                        <input type="radio" class="btn-check" name="period" id="year">
                        <label class="btn btn-outline-primary" for="year">Year</label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Room Occupancy</h5>
            </div>
            <div class="card-body">
                <canvas id="occupancyChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Recent Check-ins</h5>
                    <a href="{{ route('checkins.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCheckins ?? [] as $checkin)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $checkin->guest->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $checkin->guest->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">{{ $checkin->room->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, H:i') : 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}">
                                        {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No recent check-ins found
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
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('checkins.index') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        New Check-in
                    </a>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-plus me-2"></i>
                        New Reservation
                    </a>
                    <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-person-plus me-2"></i>
                        Add Guest
                    </a>
                    <a href="{{ route('reports.revenue') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>
                        View Reports
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">Server Status</span>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">Database</span>
                    <span class="badge bg-success">Connected</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted">Last Backup</span>
                    <span class="text-muted small">2 hours ago</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted">Storage Used</span>
                    <span class="text-muted small">68%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue',
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4500],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Occupancy Chart
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available', 'Maintenance'],
            datasets: [{
                data: [65, 30, 5],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
