@extends('layouts.main')

@section('title', 'Reports - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="page-subtitle">Comprehensive insights into your hotel's performance</p>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-1" style="color: #e0e7ff;">Total Revenue</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                        <small style="color: #e0e7ff;">This Month</small>
                    </div>
                    <div class="rounded-2 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-currency-dollar fs-4" style="color: #6366f1;"></i>
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
                        <h6 class="card-subtitle mb-1" style="color: #e0ffe0;">Occupancy Rate</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">{{ number_format($occupancyRate ?? 0, 1) }}%</h3>
                        <small style="color: #e0ffe0;">Current Month</small>
                    </div>
                    <div class="rounded-2 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-pie-chart fs-4" style="color: #10b981;"></i>
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
                        <h6 class="card-subtitle mb-1" style="color: #fde4ff;">Total Bookings</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $totalBookings ?? 0 }}</h3>
                        <small style="color: #fde4ff;">This Month</small>
                    </div>
                    <div class="rounded-2 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-calendar-check fs-4" style="color: #f43f5e;"></i>
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
                        <h6 class="card-subtitle mb-1" style="color: #e0f7fa;">Average ADR</h6>
                        <h3 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($averageADR ?? 0, 2) }}</h3>
                        <small style="color: #e0f7fa;">Daily Rate</small>
                    </div>
                    <div class="rounded-2 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-graph-up fs-4" style="color: #0ea5e9;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Categories -->
<div class="row g-4">
    <!-- Financial Reports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-currency-dollar text-primary me-2"></i>
                    Financial Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('reports.revenue') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Revenue Report</h6>
                            <small class="text-muted">Detailed revenue analysis and trends</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Profit & Loss</h6>
                            <small class="text-muted">P&L statements and financial overview</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Payment Analysis</h6>
                            <small class="text-muted">Payment methods and transaction reports</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy Reports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-house-door text-success me-2"></i>
                    Occupancy Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Room Occupancy</h6>
                            <small class="text-muted">Room utilization and availability trends</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Booking Patterns</h6>
                            <small class="text-muted">Seasonal trends and booking behavior</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">No-Show Analysis</h6>
                            <small class="text-muted">No-show rates and cancellation patterns</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Guest Reports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people text-info me-2"></i>
                    Guest Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Guest Demographics</h6>
                            <small class="text-muted">Guest profiles and demographics analysis</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Loyalty Program</h6>
                            <small class="text-muted">Repeat guests and loyalty metrics</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Guest Satisfaction</h6>
                            <small class="text-muted">Reviews and satisfaction scores</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Operational Reports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear text-warning me-2"></i>
                    Operational Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Housekeeping Report</h6>
                            <small class="text-muted">Room status and housekeeping efficiency</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Maintenance Log</h6>
                            <small class="text-muted">Maintenance requests and completion rates</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Staff Performance</h6>
                            <small class="text-muted">Staff productivity and performance metrics</small>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Export Section -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-download text-primary me-2"></i>
            Quick Export
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100" onclick="exportData('revenue')">
                    <i class="bi bi-file-earmark-excel me-2"></i>
                    Revenue Data
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-success w-100" onclick="exportData('occupancy')">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    Occupancy Report
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-info w-100" onclick="exportData('guests')">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Guest List
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-warning w-100" onclick="exportData('financial')">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                    Financial Summary
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportData(type) {
    // Add export functionality here
    alert('Exporting ' + type + ' data...');
}
</script>
@endpush
