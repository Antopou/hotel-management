@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Dashboard</h2>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Guests</h5>
                    <h2>{{ $totalGuests }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Reservations</h5>
                    <h2>{{ $totalReservations }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Rooms</h5>
                    <h2>{{ $totalRooms }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Check-ins</h5>
                    <h2>{{ $totalCheckins }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Total Folios</h5>
                    <h2>{{ $totalFolios }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Revenue</h5>
                    <h2>${{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Monthly Revenue (Last 6 Months)</h5>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="row">
        <div class="col-md-6">
            <h4>Latest Reservations</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestReservations as $r)
                        <tr>
                            <td>{{ $r->guest->name ?? 'N/A' }}</td>
                            <td>{{ $r->room->name ?? 'N/A' }}</td>
                            <td>{{ $r->checkin_date }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status === 'checked-in' ? 'success' : ($r->status === 'pending' ? 'secondary' : ($r->status === 'cancelled' ? 'danger' : 'primary')) }}">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>Latest Check-ins</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestCheckins as $c)
                        <tr>
                            <td>{{ $c->guest->name ?? 'N/A' }}</td>
                            <td>{{ $c->room->name ?? 'N/A' }}</td>
                            <td>{{ $c->checkin_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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



