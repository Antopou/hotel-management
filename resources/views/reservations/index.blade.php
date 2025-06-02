@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div class="toast text-bg-success border-0" role="alert" id="successToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="toast text-bg-danger border-0" role="alert" id="errorToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Reservations</h3>
        <a href="{{ route('reservations.create') }}" class="btn btn-success">Add New</a>
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('reservations.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="guest" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
        </div>
        <div class="col-md-4">
            <input type="date" name="checkin_date" value="{{ request('checkin_date') }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->guest->name ?? 'N/A' }}</td>
                    <td>{{ $reservation->room->name ?? 'N/A' }}</td>
                    <td>{{ $reservation->checkin_date }}</td>
                    <td>{{ $reservation->checkout_date }}</td>
                    <td>
                        <span class="badge {{ $reservation->is_checkin ? 'bg-success' : 'bg-secondary' }}">
                            {{ $reservation->is_checkin ? 'Checked In' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        {{-- View --}}
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewReservationModal{{ $reservation->id }}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        {{-- Edit --}}
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editReservationModal{{ $reservation->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        {{-- Delete --}}
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteReservationModal{{ $reservation->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                {{-- View Modal --}}
                <div class="modal fade" id="viewReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="viewReservationLabel{{ $reservation->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewReservationLabel{{ $reservation->id }}">Reservation Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Guest</dt>
                                    <dd class="col-sm-8">{{ $reservation->guest->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Room</dt>
                                    <dd class="col-sm-8">{{ $reservation->room->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Check-in Date</dt>
                                    <dd class="col-sm-8">{{ $reservation->checkin_date }}</dd>
                                    <dt class="col-sm-4">Check-out Date</dt>
                                    <dd class="col-sm-8">{{ $reservation->checkout_date }}</dd>
                                    <dt class="col-sm-4">Guests</dt>
                                    <dd class="col-sm-8">{{ $reservation->number_of_guest }}</dd>
                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8">{{ $reservation->is_checkin ? 'Checked In' : 'Pending' }}</dd>
                                </dl>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="editReservationLabel{{ $reservation->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editReservationLabel{{ $reservation->id }}">Edit Reservation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="checkin_date{{ $reservation->id }}" class="form-label">Check-in Date</label>
                                        <input type="datetime-local" name="checkin_date" id="checkin_date{{ $reservation->id }}" class="form-control" value="{{ $reservation->checkin_date }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="checkout_date{{ $reservation->id }}" class="form-label">Check-out Date</label>
                                        <input type="datetime-local" name="checkout_date" id="checkout_date{{ $reservation->id }}" class="form-control" value="{{ $reservation->checkout_date }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="number_of_guest{{ $reservation->id }}" class="form-label">Number of Guests</label>
                                        <input type="number" name="number_of_guest" id="number_of_guest{{ $reservation->id }}" class="form-control" value="{{ $reservation->number_of_guest }}">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_checkin" id="is_checkin{{ $reservation->id }}" value="1" {{ $reservation->is_checkin ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_checkin{{ $reservation->id }}">Mark as Checked In</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Delete Modal --}}
                <div class="modal fade" id="deleteReservationModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="deleteReservationLabel{{ $reservation->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteReservationLabel{{ $reservation->id }}">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the reservation for <strong>{{ $reservation->guest->name ?? 'N/A' }}</strong>?</p>
                                    <p class="text-muted">This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="7">
                        <div class="alert alert-info mb-0 text-center">No reservations found.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $reservations->links('pagination::bootstrap-5') }}
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successToastEl = document.getElementById('successToast');
        const errorToastEl = document.getElementById('errorToast');

        if (successToastEl) {
            new bootstrap.Toast(successToastEl, { autohide: true, delay: 3000 }).show();
        }

        if (errorToastEl) {
            new bootstrap.Toast(errorToastEl, { autohide: true, delay: 3000 }).show();
        }
    });
</script>
@endsection
