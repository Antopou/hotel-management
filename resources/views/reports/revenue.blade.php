@extends('layouts.main') {{-- Extend your main layout --}}

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Revenue Report</h3>
        {{-- Optional: Export button --}}
        <button class="btn btn-success shadow-sm">
            <i class="bi bi-file-earmark-arrow-down me-2"></i> Export Report
        </button>
    </div>

    {{-- Filter Form for Reports --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.revenue') }}" class="row align-items-end g-2 w-100 m-0" id="reportFilterForm" style="flex-wrap:nowrap;">
                <div class="col">
                    <label class="form-label mb-1">Filter Period</label>
                    <select name="period" class="form-select" id="reportPeriodSelect">
                        <option value="all_time" {{ request('period', 'all_time') == 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="last_7_days" {{ request('period') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="last_30_days" {{ request('period') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_3_months" {{ request('period') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last_6_months" {{ request('period') == '6_months' || request('period') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="last_year" {{ request('period') == 'last_year' ? 'selected' : '' }}>Last Year</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control" id="reportStartDateInput" value="{{ request('start_date') }}">
                </div>
                <div class="col">
                    <label class="form-label mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control" id="reportEndDateInput" value="{{ request('end_date') }}">
                </div>
                <div class="col-auto d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary" id="applyReportFiltersBtn">
                        <i class="bi bi-funnel-fill me-2"></i> Apply
                    </button>
                    <a href="{{ route('reports.revenue') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Key Revenue Metrics --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-1">Total Revenue</h5>
                    <h2 class="card-text text-dark fw-bold">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-1">Average Daily Rate (ADR)</h5>
                    <h2 class="card-text text-dark fw-bold">${{ number_format($averageDailyRate ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-1">Revenue Per Available Room (RevPAR)</h5>
                    <h2 class="card-text text-dark fw-bold">${{ number_format($revPar ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Trends Chart --}}
    <div class="card shadow-sm border-0 h-100 mb-4">
        <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Revenue Trends Over Period</h5>
            <canvas id="revenueTrendChart" height="100"></canvas>
        </div>
    </div>

    {{-- Optional: Detailed Revenue Table (example structure) --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Revenue Breakdown</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-primary">Date / Month</th>
                            <th class="text-primary">Room Revenue</th>
                            <th class="text-primary">F&B Revenue</th>
                            <th class="text-primary">Other Revenue</th>
                            <th class="text-primary">Total Daily/Monthly Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop through your revenue data here --}}
                        @forelse($revenueDetails ?? [] as $detail)
                            <tr>
                                <td>{{ $detail['date_or_month'] }}</td>
                                <td>${{ number_format($detail['room_revenue'], 2) }}</td>
                                <td>${{ number_format($detail['fb_revenue'], 2) }}</td>
                                <td>${{ number_format($detail['other_revenue'], 2) }}</td>
                                <td>${{ number_format($detail['total_revenue'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No revenue data available for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('reportPeriodSelect');
    const startDateInput = document.getElementById('reportStartDateInput');
    const endDateInput = document.getElementById('reportEndDateInput');
    const filterForm = document.getElementById('reportFilterForm');

    // Filter logic similar to dashboard
    periodSelect.addEventListener('change', function() {
        if (this.value !== 'custom') {
            startDateInput.value = '';
            endDateInput.value = '';
        }
    });

    filterForm.addEventListener('submit', function(event) {
        const startDateHasValue = startDateInput.value !== '';
        const endDateHasValue = endDateInput.value !== '';

        if ((startDateHasValue || endDateHasValue) && periodSelect.value !== 'custom') {
            periodSelect.value = 'custom';
        }
    });

    // Chart.js for Revenue Trend Chart
    // You'll need to pass 'revenue_labels' (e.g., dates or months) and 'revenue_data'
    // from your controller. Assuming they are available in window.reportData.
    window.reportData = {
        revenueLabels: @json($revenueLabels ?? []), // e.g., ['Jan', 'Feb', 'Mar'] or ['2023-01-01', '2023-01-02']
        revenueValues: @json($revenueValues ?? [])  // e.g., [1000, 1200, 950]
    };

    if (window.reportData.revenueLabels.length > 0) {
        new Chart(document.getElementById('revenueTrendChart').getContext('2d'), {
            type: 'line', // Can be 'bar' or 'line'
            data: {
                labels: window.reportData.revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: window.reportData.revenueValues,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: true, // Fill area under the line
                    tension: 0.4 // Smooth line curves
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Period'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide legend if only one dataset
                    }
                }
            }
        });
    }
});
</script>
@endsection

@push('styles')
<style>
/* Custom styles specific to reports page, if needed */
#reportFilterForm {
    width: 100%;
    margin: 0;
    flex-wrap: nowrap !important; /* Keep filter on one line horizontally */
    overflow-x: auto; /* Allow scrolling if screen is too small */
}
.card-body {
    padding: 1.25rem; /* Revert default padding for report cards if 0 !important is too restrictive */
}
/* If you want the filter card body to have no padding, keep the global .card-body { padding: 0 !important; } from your main layout or apply it selectively.
   For this specific report filter, you might want padding. You can add a specific ID or class to the filter card for targeted styling.
   e.g., #reportFilterCard .card-body { padding: 0; }
*/
</style>
@endpush