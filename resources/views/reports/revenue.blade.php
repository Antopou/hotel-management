@extends('layouts.main')

@section('title', 'Revenue Report - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Revenue</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1>Revenue Report</h1>
    <div>
        <a href="{{ route('reports.revenue.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
        </a>
        <a href="{{ route('reports.revenue.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.revenue') }}">
            <input type="hidden" name="group_by" id="group_by" value="{{ request('group_by', 'daily') }}">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="period" class="form-label">Period</label>
                    <select name="period" id="period" class="form-select" onchange="toggleCustomDates()">
                        <option value="this_month" {{ request('period', 'this_month') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_7_days" {{ request('period') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="last_30_days" {{ request('period') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="last_3_months" {{ request('period') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last_6_months" {{ request('period') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="last_year" {{ request('period') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                        <option value="all_time" {{ request('period') == 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div class="col-12 col-md-3" id="startDateCol">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-12 col-md-3" id="endDateCol">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>
                <div class="col-12 col-md-3">
                    <label for="room_type" class="form-label">Room Type</label>
                    <select name="room_type" id="room_type" class="form-select">
                        <option value="">All Room Types</option>
                        @foreach($roomTypes ?? [] as $type)
                            <option value="{{ $type->room_type_code }}" {{ request('room_type') == $type->room_type_code ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
function toggleCustomDates() {
    var period = document.getElementById('period').value;
    var startCol = document.getElementById('startDateCol');
    var endCol = document.getElementById('endDateCol');
    if (period === 'custom') {
        startCol.style.display = '';
        endCol.style.display = '';
    } else {
        startCol.style.display = 'none';
        endCol.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', toggleCustomDates);
</script>

<!-- Revenue Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0e7ff;">Total Revenue</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                        <small style="color: #e0e7ff;">
                            <i class="bi bi-arrow-up me-1"></i>
                            +12% from last period
                        </small>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-currency-dollar fs-2" style="color: #6366f1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0ffe0;">Room Revenue</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($roomRevenue ?? 0, 2) }}</h2>
                        <small style="color: #e0ffe0;">
                            {{ $totalRevenue > 0 ? number_format(($roomRevenue ?? 0) / $totalRevenue * 100, 1) : '0.0' }}% of total
                        </small>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-house-door fs-2" style="color: #10b981;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #fde4ff;">Average Daily Rate</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($averageDailyRate ?? 0, 2) }}</h2>
                        <small style="color: #fde4ff;">
                            <i class="bi bi-arrow-up me-1"></i>
                            +5% from last period
                        </small>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-graph-up fs-2" style="color: #f43f5e;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0f7fa;">Occupancy Rate</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">{{ number_format($occupancyRate ?? 0, 1) }}%</h2>
                        <small style="color: #e0f7fa;">
                            <i class="bi bi-arrow-up me-1"></i>
                            +3% from last period
                        </small>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-pie-chart fs-2" style="color: #0ea5e9;"></i>
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
                    <h5 class="card-title mb-0">Revenue Trend</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary {{ request('group_by', 'daily') == 'daily' ? 'active' : '' }}" onclick="setGroupBy('daily')">Daily</button>
                        <button type="button" class="btn btn-outline-primary {{ request('group_by') == 'weekly' ? 'active' : '' }}" onclick="setGroupBy('weekly')">Weekly</button>
                        <button type="button" class="btn btn-outline-primary {{ request('group_by') == 'monthly' ? 'active' : '' }}" onclick="setGroupBy('monthly')">Monthly</button>
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
                <h5 class="card-title mb-0">Revenue by Room Type</h5>
            </div>
            <div class="card-body">
                <canvas id="roomTypeChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Details Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Revenue Details</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Room Type</th>
                        <th>Rooms Sold</th>
                        <th>Average Rate</th>
                        <th>Revenue</th>
                        <th>Occupancy %</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($revenueDetails ?? [] as $detail)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($detail->date)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $detail->room_type_name ?? 'All Types' }}
                            </span>
                        </td>
                        <td>{{ $detail->rooms_sold ?? 0 }}</td>
                        <td>${{ number_format($detail->average_rate ?? 0, 2) }}</td>
                        <td><strong class="text-success">${{ number_format($detail->revenue ?? 0, 2) }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $detail->occupancy_rate ?? 0 }}%"></div>
                                </div>
                                <span class="text-sm">{{ number_format($detail->occupancy_rate ?? 0, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-graph-down fs-1 d-block mb-2"></i>
                                No revenue data found for the selected period
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function setGroupBy(val) {
    document.getElementById('group_by').value = val;
    document.querySelector('form[action="{{ route('reports.revenue') }}"]').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Revenue',
                data: @json($chartData ?? []),
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
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
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

    // Room Type Revenue Chart
    const roomTypeCtx = document.getElementById('roomTypeChart').getContext('2d');
    new Chart(roomTypeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($roomTypeLabels ?? []),
            datasets: [{
                data: @json($roomTypeData ?? []),
                backgroundColor: [
                    '#2563eb',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ],
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

@push('styles')
<style>
/* Remove horizontal scroll and make filter responsive */
@media (max-width: 991.98px) {
    .card-body form .col-md-3,
    .card-body form .col-12 {
        min-width: 100% !important;
    }
    .card-body form .d-flex {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
}
</style>
@endpush
