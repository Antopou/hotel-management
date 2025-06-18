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
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: #6366f1;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; color: #fff;">Total Rooms</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $totalRooms ?? 0 }}</h3>
                        <small style="font-size: 0.7rem; color: #ede9fe;">
                            Available: {{ $availableRooms ?? 0 }}
                        </small>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0,0,0,0.08);">
                        <i class="bi bi-door-open fs-5" style="color: #fff;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: #f59e42;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; color: #fff;">Total Reservations</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $totalReservations ?? 0 }}</h3>
                        <small style="font-size: 0.7rem; color: #fff;">
                            Pending: {{ $pendingReservations ?? 0 }}
                        </small>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0,0,0,0.08);">
                        <i class="bi bi-calendar-check fs-5" style="color: #fff;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: #38bdf8;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; color: #fff;">Total Guests</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $totalGuests ?? 0 }}</h3>
                        <small style="font-size: 0.7rem; color: #fff;">
                            Checked In: {{ $totalCheckins ?? 0 }}
                        </small>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0,0,0,0.08);">
                        <i class="bi bi-people fs-5" style="color: #fff;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: #34d399;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1" style="font-size: 0.75rem; color: #fff;">Revenue</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        <small style="font-size: 0.7rem; color: #fff;">
                            Folios: {{ $totalFolios ?? 0 }}
                        </small>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(0,0,0,0.08);">
                        <i class="bi bi-currency-dollar fs-5" style="color: #fff;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" style="font-size: 1rem;">Revenue Overview</h5>
                    <form method="GET" id="periodForm" class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="period" id="week" value="this_week" {{ request('period', 'this_week') == 'this_week' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="week" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Week</label>
                        <input type="radio" class="btn-check" name="period" id="month" value="this_month" {{ request('period') == 'this_month' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="month" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Month</label>
                        <input type="radio" class="btn-check" name="period" id="year" value="this_year" {{ request('period') == 'this_year' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="year" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Year</label>
                    </form>
                </div>
            </div>
            <div class="card-body p-3">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header p-3">
                <h5 class="card-title mb-0" style="font-size: 1rem;">Room Occupancy</h5>
            </div>
            <div class="card-body p-3">
                <canvas id="occupancyChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0" style="font-size: 1rem;">Recent Check-ins</h5>
                    <a href="{{ route('checkins.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="font-size: 0.8rem;">Guest</th>
                                <th style="font-size: 0.8rem;">Room</th>
                                <th style="font-size: 0.8rem;">Check-in</th>
                                <th style="font-size: 0.8rem;">Status</th>
                                {{-- Actions column removed --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestCheckins ?? [] as $checkin)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-primary" style="font-size: 0.875rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0" style="font-size: 0.875rem;">{{ $checkin->guest->name ?? 'N/A' }}</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $checkin->guest->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info" style="font-size: 0.75rem;">{{ $checkin->room->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, H:i') : 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}" style="font-size: 0.7rem;">
                                        {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                                    </span>
                                </td>
                                {{-- Actions column removed --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <span style="font-size: 0.875rem;">No recent check-ins found</span>
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
            <div class="card-header p-3">
                <h5 class="card-title mb-0" style="font-size: 1rem;">Quick Actions</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('checkins.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        New Check-in
                    </a>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar-plus me-2"></i>
                        New Reservation
                    </a>
                    <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person-plus me-2"></i>
                        Add Guest
                    </a>
                    <a href="{{ route('reports.revenue') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-graph-up me-2"></i>
                        View Reports
                    </a>
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
    // Revenue Chart (dynamic)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($months ?? []),
            datasets: [{
                label: 'Revenue',
                data: @json($revenues ?? []),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
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
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Occupancy Chart (dynamic)
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available', 'Maintenance'],
            datasets: [{
                data: [
                    {{ $occupiedRooms ?? 0 }},
                    {{ $availableRooms ?? 0 }},
                    {{ $maintenanceRooms ?? 0 }}
                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Pass check-in ID to show modal
    var showModal = document.getElementById('showCheckinModal');
    if (showModal) {
        showModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var checkinId = button.getAttribute('data-checkin-id');
            // You can now use checkinId to load data via AJAX or set a hidden input
            // Example: $('#showCheckinModal').find('input[name="checkin_id"]').val(checkinId);
        });
    }

    // Pass check-in ID to edit modal
    var editModal = document.getElementById('editCheckinModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var checkinId = button.getAttribute('data-checkin-id');
            // You can now use checkinId to load data via AJAX or set a hidden input
        });
    }
});
</script>
<script>
document.querySelectorAll('#periodForm input[type=radio]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('periodForm').submit();
    });
});
</script>
@endpush
