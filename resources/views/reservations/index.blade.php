@extends('layouts.main')

@section('title', 'Reservations - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reservations</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Reservations</h1>
        <p class="page-subtitle">Manage hotel reservations and bookings</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReservationModal">
        <i class="bi bi-calendar-plus me-2"></i>
        New Reservation
    </button>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0e7ff;">Total Reservations</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $reservations->total() }}</h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-calendar-check fs-2" style="color: #6366f1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e42 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #fff7e6;">Pending</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">
                            {{ $pendingCount ?? \App\Models\GuestReservation::where('status', 'pending')->count() }}
                        </h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-clock fs-2" style="color: #f59e42;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0f7fa;">Confirmed</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">
                            {{ $confirmedCount ?? \App\Models\GuestReservation::where('status', 'confirmed')->count() }}
                        </h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-check-circle fs-2" style="color: #0ea5e9;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #34d399 0%, #6ee7b7 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0ffe0;">Today's Arrivals</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">
                            {{ $todayArrivals ?? \App\Models\GuestReservation::whereDate('checkin_date', now()->toDateString())->count() }}
                        </h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-calendar-event fs-2" style="color: #34d399;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reservations.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="guest" class="form-label">Guest Name</label>
                <input type="text" name="guest" id="guest" value="{{ request('guest') }}" 
                       class="form-control" placeholder="Search by guest name">
            </div>
            <div class="col-md-3">
                <label for="checkin_date" class="form-label">Check-in Date</label>
                <input type="date" name="checkin_date" id="checkin_date" value="{{ request('checkin_date') }}" 
                       class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reservations Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Status</th>
                        <th>Total</th> <!-- Add this line -->
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $reservation->guest->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $reservation->guest->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $reservation->room->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('M d, Y') : 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('H:i') : '' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $reservation->checkout_date ? \Carbon\Carbon::parse($reservation->checkout_date)->format('M d, Y') : 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $reservation->checkout_date ? \Carbon\Carbon::parse($reservation->checkout_date)->format('H:i') : '' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $reservation->number_of_nights }}
                                {{ Str::plural('Night', $reservation->number_of_nights) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($reservation->status ?? 'pending') {
                                    'confirmed' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    'completed' => 'bg-info',
                                    default => 'bg-warning'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($reservation->status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <strong>
                                ${{ number_format($reservation->total_amount ?? 0, 2) }}
                            </strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewReservationModal{{ $reservation->id }}" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editReservationModal{{ $reservation->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($reservation->status !== 'cancelled')
                                <button class="btn btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmReservationModal{{ $reservation->id }}"
                                        title="Confirm">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                @endif
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteReservationModal{{ $reservation->id }}" title="Cancel">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3 text-muted"></i>
                                <h5>No reservations found</h5>
                                <p>Start by creating your first reservation</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReservationModal">
                                    <i class="bi bi-calendar-plus me-2"></i>Create Reservation
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($reservations->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $reservations->links('pagination::bootstrap-5') }}
</div>
@endif

@include('reservations._modal_create')

<!-- Confirm Reservation Modal -->
<div class="modal fade" id="confirmReservationModal" tabindex="-1" aria-labelledby="confirmReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmReservationModalLabel">Confirm Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to confirm this reservation?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmReservationBtn">Yes, Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Place all modals here, outside the table -->
@foreach($reservations as $reservation)
    @include('reservations._modals', ['reservation' => $reservation, 'guests' => $guests ?? [], 'rooms' => $rooms ?? []])
@endforeach
@endsection

@push('scripts')
<script>
function confirmReservation(id) {
    if (confirm('Are you sure you want to confirm this reservation?')) {
        fetch(`/reservations/${id}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to confirm reservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var confirmReservationModal = document.getElementById('confirmReservationModal');
    var confirmReservationBtn = document.getElementById('confirmReservationBtn');

    confirmReservationModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var reservationId = button.getAttribute('data-reservation-id');

        confirmReservationBtn.onclick = function () {
            fetch(`/reservations/${reservationId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to confirm reservation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    });
});
</script>
@endpush
