@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    {{-- Dashboard Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Dashboard Overview</h3>
        <a href="{{ route('frontdesk.index') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Go to Frontdesk
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}" class="row align-items-end g-2 w-100 m-0" id="filterForm" style="flex-wrap:nowrap;">
                <div class="col">
                    <label class="form-label mb-1">Filter Period</label>
                    <select name="period" class="form-select" id="periodSelect">
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
                    <input type="date" name="start_date" class="form-control" id="startDateInput" value="{{ request('start_date') }}">
                </div>
                <div class="col">
                    <label class="form-label mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control" id="endDateInput" value="{{ request('end_date') }}">
                </div>
                <div class="col-auto d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary" id="applyFiltersBtn">
                        <i class="bi bi-search me-2"></i> Apply
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Rest of your dashboard content remains the same --}}
    <div class="dashboard-main-wrapper">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 mb-4 g-3">
            {{-- Total Guests Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('guests.index') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Guests</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalGuests }}</h2>
                    </div>
                </a>
            </div>
            {{-- Total Reservations Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('reservations.index') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-info-subtle text-info mb-3">
                            <i class="bi bi-calendar-check-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Reservations</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalReservations }}</h2>
                    </div>
                </a>
            </div>
            {{-- Total Rooms Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('rooms.index') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-success-subtle text-success mb-3">
                            <i class="bi bi-house-door-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Rooms</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalRooms }}</h2>
                    </div>
                </a>
            </div>
            {{-- Total Check-ins Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('checkins.index') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-arrow-right-square-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Check-ins</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalCheckins }}</h2>
                    </div>
                </a>
            </div>
            {{-- Total Folios Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('folios.index') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-secondary-subtle text-secondary mb-3">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Folios</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalFolios }}</h2>
                    </div>
                </a>
            </div>
            {{-- Revenue Card --}}
            <div class="col">
                {{-- Wrap the card in an <a> tag --}}
                <a href="{{ route('reports.revenue') }}" class="card text-center h-100 shadow-sm border-0 card-hover text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-danger-subtle text-danger mb-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Revenue</h5>
                        <h2 class="card-text text-dark fw-bold">${{ number_format($totalRevenue, 2) }}</h2>
                    </div>
                </a>
            </div>
        </div>

        {{-- Multiple Charts Section --}}
        <div class="row g-3 mb-4">
            {{-- Revenue (Bar Chart) --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Monthly Revenue</h5>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            {{-- Occupancy Rate (Line Chart) --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Occupancy Rate (%)</h5>
                        <canvas id="occupancyChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            {{-- ADR and RevPAR (Bar Chart) --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">ADR & RevPAR</h5>
                        <canvas id="adrRevparChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            {{-- Circle Graph: Current Month Occupancy --}}
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold mb-3">Current Month Occupancy</h5>
                        <div style="max-width:180px; margin:auto;">
                            <canvas id="occupancyDoughnut" width="180" height="180" style="display:block; margin:auto;"></canvas>
                        </div>
                        <div class="fs-4 fw-bold mt-3" id="occupancyPercent"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Latest Reservations</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-primary">Guest</th>
                                        <th class="text-primary">Room</th>
                                        <th class="text-primary">Check-in</th>
                                        <th class="text-primary">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestReservations as $r)
                                        <tr>
                                            <td>{{ $r->guest->name ?? 'N/A' }}</td>
                                            <td>{{ $r->room->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($r->checkin_date)->format('Y-m-d H:i') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    switch ($r->status) {
                                                        case 'checked-in': $statusClass = 'success'; break;
                                                        case 'pending': $statusClass = 'warning'; break;
                                                        case 'cancelled': $statusClass = 'danger'; break;
                                                        case 'checked-out': $statusClass = 'info'; break;
                                                        default: $statusClass = 'primary'; break;
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucfirst(str_replace('-', ' ', $r->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($latestReservations->isEmpty())
                                <p class="text-center text-muted mt-3 mb-0">No recent reservations found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Latest Check-ins</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-primary">Guest</th>
                                        <th class="text-primary">Room</th>
                                        <th class="text-primary">Check-in</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestCheckins as $c)
                                        <tr>
                                            <td>{{ $c->guest->name ?? 'N/A' }}</td>
                                            <td>{{ $c->room->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($c->checkin_date)->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($latestCheckins->isEmpty())
                                <p class="text-center text-muted mt-3 mb-0">No recent check-ins found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    window.dashboardData = {
        months: @json($months),
        revenues: @json($revenues),
        occupancies: @json($occupancies),
        adrs: @json($adrs),
        revpars: @json($revpars)
    };
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const periodSelect = document.getElementById('periodSelect');
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');
    const filterForm = document.getElementById('filterForm');

    // --- New Feature: Clear dates when period changes away from 'Custom Range' ---
    periodSelect.addEventListener('change', function() {
        if (this.value !== 'custom') {
            startDateInput.value = ''; // Clear start date
            endDateInput.value = '';   // Clear end date
        }
    });

    // --- Existing Feature: Set period to 'Custom Range' if dates are used on submit ---
    filterForm.addEventListener('submit', function(event) {
        const startDateHasValue = startDateInput.value !== '';
        const endDateHasValue = endDateInput.value !== '';

        if ((startDateHasValue || endDateHasValue) && periodSelect.value !== 'custom') {
            periodSelect.value = 'custom';
        }
    });

    if (!window.dashboardData) return;

    // Chart.js initialization (remains unchanged)
    // Revenue Bar Chart
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: window.dashboardData.months,
            datasets: [{
                label: 'Revenue',
                data: window.dashboardData.revenues,
                borderWidth: 2,
                backgroundColor: 'rgba(40,167,69,0.5)',
                borderColor: '#28a745',
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: { y: { beginAtZero: true } }
        }
    });

    // Occupancy Line Chart
    new Chart(document.getElementById('occupancyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: window.dashboardData.months,
            datasets: [{
                label: 'Occupancy (%)',
                data: window.dashboardData.occupancies,
                borderWidth: 3,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });

    // ADR & RevPAR Bar Chart
    new Chart(document.getElementById('adrRevparChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: window.dashboardData.months,
            datasets: [
                {
                    label: 'ADR',
                    data: window.dashboardData.adrs,
                    backgroundColor: 'rgba(255,193,7,0.5)',
                    borderColor: '#ffc107',
                    borderWidth: 1
                },
                {
                    label: 'RevPAR',
                    data: window.dashboardData.revpars,
                    backgroundColor: 'rgba(220,53,69,0.5)',
                    borderColor: '#dc3545',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: { legend: { display: true }},
            scales: { y: { beginAtZero: true } }
        }
    });

    // Circle (Doughnut) for Current Month Occupancy
    const occupancyArr = window.dashboardData.occupancies;
    const currOccupancy = occupancyArr && occupancyArr.length > 0 ? occupancyArr[occupancyArr.length - 1] : 0;
    document.getElementById('occupancyPercent').innerText = currOccupancy + '%';

    new Chart(document.getElementById('occupancyDoughnut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Vacant'],
            datasets: [{
                data: [currOccupancy, 100 - currOccupancy],
                backgroundColor: ['#0d6efd', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection

@push('styles')
<style>
.card-body {
    padding: 0 !important;
}
#filterForm {
    width: 100%;
    margin: 0;
    flex-wrap: nowrap !important;
    overflow-x: auto;
}
#occupancyDoughnut {
    max-width: 140px !important;
    max-height: 140px !important;
    margin: 0 auto;
}
/* Add custom style for clickable cards to remove text decoration */
.card-hover.text-decoration-none {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.card-hover.text-decoration-none:hover {
    transform: translateY(-5px); /* Optional: slight lift on hover */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Optional: stronger shadow on hover */
    cursor: pointer; /* Indicate it's clickable */
}
</style>
@endpush