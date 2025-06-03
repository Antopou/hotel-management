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
        <h3 class="bold m-0">Guest Check-ins</h3>
        <!-- Button to trigger Create Modal -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCheckinModal">Add New</button>
    </div>
    <!-- Create Modal -->
    <div class="modal fade" id="createCheckinModal" tabindex="-1" aria-labelledby="createCheckinLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <form action="{{ route('checkins.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCheckinLabel">Add New Check-in</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Guest</label>
                            <select name="guest_code" class="form-select" required>
                                @foreach ($guests as $guest)
                                    <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <select name="room_code" class="form-select" required>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check-in Date</label>
                            <input type="datetime-local" name="checkin_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Guests</label>
                            <input type="number" name="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rate</label>
                            <input type="number" step="0.01" name="rate" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Payment</label>
                            <input type="number" step="0.01" name="total_payment" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <input type="text" name="payment_method" class="form-control">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_checkout" value="1">
                            <label class="form-check-label">Mark as Checked Out</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('checkins.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="guest" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
        </div>
        <div class="col-md-4">
            <input type="date" name="checkin_date" value="{{ request('checkin_date') }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
            @forelse($checkins as $checkin)
                <tr>
                    <td>{{ $checkin->id }}</td>
                    <td>{{ $checkin->guest->name ?? 'N/A' }}</td>
                    <td>{{ $checkin->room->name ?? 'N/A' }}</td>
                    <td>{{ $checkin->checkin_date }}</td>
                    <td>{{ $checkin->checkout_date }}</td>
                    <td>
                        <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}">
                            {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                        </span>
                    </td>
                    <td>
                        {{-- View --}}
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewCheckinModal{{ $checkin->id }}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        {{-- Edit --}}
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editCheckinModal{{ $checkin->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        {{-- Delete --}}
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCheckinModal{{ $checkin->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                {{-- View Modal --}}
                <div class="modal fade" id="viewCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="viewCheckinLabel{{ $checkin->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewCheckinLabel{{ $checkin->id }}">Check-in Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Guest</dt>
                                    <dd class="col-sm-8">{{ $checkin->guest->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Room</dt>
                                    <dd class="col-sm-8">{{ $checkin->room->name ?? 'N/A' }}</dd>
                                    <dt class="col-sm-4">Check-in</dt>
                                    <dd class="col-sm-8">{{ $checkin->checkin_date }}</dd>
                                    <dt class="col-sm-4">Check-out</dt>
                                    <dd class="col-sm-8">{{ $checkin->checkout_date }}</dd>
                                    <dt class="col-sm-4">Guests</dt>
                                    <dd class="col-sm-8">{{ $checkin->number_of_guest }}</dd>
                                    <dt class="col-sm-4">Rate</dt>
                                    <dd class="col-sm-8">{{ $checkin->rate ?? '0.00' }}</dd>
                                    <dt class="col-sm-4">Total Payment</dt>
                                    <dd class="col-sm-8">{{ $checkin->total_payment ?? '0.00' }}</dd>
                                    <dt class="col-sm-4">Payment Method</dt>
                                    <dd class="col-sm-8">{{ $checkin->payment_method ?? '-' }}</dd>
                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8">{{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}</dd>
                                </dl>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="editCheckinLabel{{ $checkin->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <form action="{{ route('checkins.update', $checkin->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCheckinLabel{{ $checkin->id }}">Edit Check-in</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Guest</label>
                                        <select name="guest_code" class="form-select" required>
                                            @foreach ($guests as $guest)
                                                <option value="{{ $guest->guest_code }}" {{ $checkin->guest_code === $guest->guest_code ? 'selected' : '' }}>
                                                    {{ $guest->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Room</label>
                                        <select name="room_code" class="form-select" required>
                                            @foreach ($rooms as $room)
                                                <option value="{{ $room->room_code }}" {{ $checkin->room_code === $room->room_code ? 'selected' : '' }}>
                                                    {{ $room->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Check-in Date</label>
                                        <input type="datetime-local" name="checkin_date" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Check-out Date</label>
                                        <input type="datetime-local" name="checkout_date" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkout_date)->format('Y-m-d\TH:i') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Guests</label>
                                        <input type="number" name="number_of_guest" class="form-control" value="{{ $checkin->number_of_guest }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rate</label>
                                        <input type="number" step="0.01" name="rate" class="form-control" value="{{ $checkin->rate ?? 0 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Total Payment</label>
                                        <input type="number" step="0.01" name="total_payment" class="form-control" value="{{ $checkin->total_payment ?? 0 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <input type="text" name="payment_method" class="form-control" value="{{ $checkin->payment_method }}">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_checkout" value="1" {{ $checkin->is_checkout ? 'checked' : '' }}>
                                        <label class="form-check-label">Mark as Checked Out</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Delete Modal --}}
                <div class="modal fade" id="deleteCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="deleteCheckinLabel{{ $checkin->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form action="{{ route('checkins.destroy', $checkin->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteCheckinLabel{{ $checkin->id }}">Confirm Delete</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the check-in for <strong>{{ $checkin->guest->name ?? 'N/A' }}</strong>?</p>
                                    <p class="text-muted">This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="7">
                        <div class="alert alert-info text-center mb-0">No check-ins found.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $checkins->links('pagination::bootstrap-5') }}
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
