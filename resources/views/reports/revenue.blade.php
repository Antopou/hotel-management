@extends('layouts.main')

@section('title', 'Revenue Report - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Revenue</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Revenue Report</h1>
        <p class="page-subtitle">Analyze your hotel's financial performance</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" onclick="exportReport()">
            <i class="bi bi-download me-2"></i>
            Export PDF
        </button>
        <button class="btn btn-outline-success" onclick="exportExcel()">
            <i class="bi bi-file-earmark-excel me-2"></i>
            Export Excel
        </button>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.revenue') }}" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                       class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                       class="form-control">
            </div>
            <div class="col-md-3">
                <label for="room_type" class="form-label">Room Type</label>
                <select name="room_type" id="room_type" class="form-select">
                    <option value="">All Room Types</option>
                    @foreach($roomTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ request('room_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Generate Report
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="setQuickDate('today')">Today</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setQuickDate('week')">Week</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setQuickDate('month')">Month</button>
            </div>
        </form>
    </div>
</div>

<!-- Revenue Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2 text-white-50">Total Revenue</h6>
                        <h2 class="card-title mb-0 fw-bold">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            +12% from last period
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-currency-dollar fs-2"></i>
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
                        <h6 class="card-subtitle mb-2 text-white-50">Room Revenue</h6>
                        <h2 class="card-title mb-0 fw-bold">${{ number_format($roomRevenue ?? 0, 2) }}</h2>
                        <small class="text-white-50">
                            {{ number_format(($roomRevenue ?? 0) / ($totalRevenue ?? 1) * 100, 1) }}% of total
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-house-door fs-2"></i>
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
                        <h6 class="card-subtitle mb-2 text-white-50">Average Daily Rate</h6>
                        <h2 class="card-title mb-0 fw-bold">${{ number_format($averageDailyRate ?? 0, 2) }}</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            +5% from last period
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-graph-up fs-2"></i>
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
                        <h6 class="card-subtitle mb-2 text-white-50">Occupancy Rate</h6>
                        <h2 class="card-title mb-0 fw-bold">{{ number_format($occupancyRate ?? 0, 1) }}%</h2>
                        <small class="text-white-50">
                            <i class="bi bi-arrow-up me-1"></i>
                            +3% from last period
                        </small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3 p-3">
                        <i class="bi bi-pie-chart fs-2"></i>
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
                        <input type="radio" class="btn-check" name="chartPeriod" id="daily" checked>
                        <label class="btn btn-outline-primary" for="daily">Daily</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="weekly">
                        <label class="btn btn-outline-primary" for="weekly">Weekly</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="monthly">
                        <label class="btn btn-outline-primary" for="monthly">Monthly</label>
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

function setQuickDate(period) {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const today = new Date();
    
    switch(period) {
        case 'today':
            startDate.value = today.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'week':
            const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
            const weekEnd = new Date(today.setDate(today.getDate() - today.getDay() + 6));
            startDate.value = weekStart.toISOString().split('T')[0];
            endDate.value = weekEnd.toISOString().split('T')[0];
            break;
        case 'month':
            const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            const monthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            startDate.value = monthStart.toISOString().split('T')[0];
            endDate.value = monthEnd.toISOString().split('T')[0];
            break;
    }
}

function exportReport() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.open(`${window.location.pathname}?${params.toString()}`, '_blank');
}

function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.open(`${window.location.pathname}?${params.toString()}`, '_blank');
}
</script>
@endpush
