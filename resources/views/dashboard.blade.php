@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    {{-- Dashboard Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Dashboard Overview</h3>
        {{-- <button class="btn btn-primary shadow-sm"><i class="bi bi-file-earmark-bar-graph me-2"></i> View Full Reports</button> --}}
    </div>

    {{-- Stats Cards with Icons and Subtle Styling --}}
    {{-- Using the most balanced row-cols for responsiveness --}}
    {{-- Wrapped dashboard cards and sections in .dashboard-main-wrapper for controlled overflow --}}
    <div class="dashboard-main-wrapper">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 mb-4 g-3">
            {{-- Total Guests Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Guests</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalGuests }}</h2>
                    </div>
                </div>
            </div>
            {{-- Total Reservations Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-info-subtle text-info mb-3">
                            <i class="bi bi-calendar-check-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Reservations</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalReservations }}</h2>
                    </div>
                </div>
            </div>
            {{-- Total Rooms Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-success-subtle text-success mb-3">
                            <i class="bi bi-house-door-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Rooms</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalRooms }}</h2>
                    </div>
                </div>
            </div>
            {{-- Total Check-ins Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-arrow-right-square-fill fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Check-ins</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalCheckins }}</h2>
                    </div>
                </div>
            </div>
            {{-- Total Folios Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-secondary-subtle text-secondary mb-3">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Total Folios</h5>
                        <h2 class="card-text text-dark fw-bold">{{ $totalFolios }}</h2>
                    </div>
                </div>
            </div>
            {{-- Revenue Card --}}
            <div class="col">
                <div class="card text-center h-100 shadow-sm border-0 card-hover">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-circle bg-danger-subtle text-danger mb-3">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h5 class="card-title text-muted mb-1">Revenue</h5>
                        <h2 class="card-text text-dark fw-bold">${{ number_format($totalRevenue, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">Monthly Revenue (Last 6 Months)</h5>
                <canvas id="revenueChart" height="80"></canvas>
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
<script>
    window.dashboardData = {
        months: @json($months),
        revenues: @json($revenues)
    };
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection