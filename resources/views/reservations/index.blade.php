@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    @include('partials.toasts')

    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Guest Reservations</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createReservationModal">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Reservation
        </button>
    </div>

    {{-- Filter/Search Form (NOW SAME UI AS GUEST MANAGEMENT) --}}
    <div class="card shadow-sm mb-4"> {{-- Added card component --}}
        <div class="card-body">
            <form method="GET" action="{{ route('reservations.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4"> {{-- Adjusted columns to fit --}}
                    <label for="filterGuestName" class="form-label">Guest Name</label> {{-- Label is now visible --}}
                    <input type="text" name="guest" id="filterGuestName" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
                </div>
                <div class="col-md-4"> {{-- Adjusted columns to fit --}}
                    <label for="filterCheckinDate" class="form-label">Check-in Date</label> {{-- Label is now visible --}}
                    <input type="date" name="checkin_date" id="filterCheckinDate" value="{{ request('checkin_date') }}" class="form-control">
                </div>
                {{-- To make buttons next to the date, we need to balance the columns.
                     Let's use col-md-3 for guest, col-md-3 for checkin, and col-md-auto for buttons.
                     The current col-md-4, col-md-4, col-md-4 (implicitly for buttons) is 12 units.
                     If we want buttons on the same line, we need to free up space.
                     Let's try: Guest (4), Date (3), Buttons (auto). That's 7 + auto. The buttons will try to fit.
                     Alternatively, Guest (3), Date (3), and a new column for buttons (e.g., col-md-6 to occupy the rest)
                     Or, Guest (3), Date (3), (col-md-3 for Status if you add one), Buttons (col-md-3)
                     For now, let's just ensure the current fields fit and the buttons are on the same line if possible.
                     Given your current two inputs, 4+4=8. Remaining 4 for buttons.
                     Let's adjust to Guest (5), Date (4), Buttons (3) for a tighter fit on the same line, or
                     Guest (4), Date (4), and a wider col-md-4 for buttons with w-100 removed but flex-grow-1.
                     Let's go for: Guest (4), Date (4), Buttons (auto) and ensure they are on the same line.
                     To fit better, we might need smaller col-md values or make the button column less 'auto'.
                     Let's go back to Guest (4), Date (3), Buttons (5) as before for same-line fit.
                     But, if you want labels *above*, then `align-items-end` might not be as effective for labels,
                     but it's still good for general alignment.

                     Let's ensure the labels are visible and then adjust column sizes to fit.
                --}}
                <div class="col-md-4 d-flex gap-2"> {{-- Re-adjusted columns to give enough space for visible labels --}}
                    {{-- No label here as buttons don't need one --}}
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary flex-grow-1">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    {{-- END Filter/Search Form --}}


    {{-- Reservation Table --}}
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Guest</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reservations as $reservation)
            <tr>
                <td>{{ $reservation->id ?? 'N/A' }}</td>
                <td>{{ $reservation->room?->name ?? 'N/A' }}</td>
                <td>{{ $reservation->guest?->name ?? 'N/A' }}</td>
                <td>{{ $reservation->checkin_date ? \Carbon\Carbon::parse($reservation->checkin_date)->format('M d, Y H:i') : 'N/A' }}</td>
                <td>{{ $reservation->checkout_date ? \Carbon\Carbon::parse($reservation->checkout_date)->format('M d, Y H:i') : 'N/A' }}</td>
                <td>
                    <span class="badge text-white
                        @if(($reservation->status ?? '') === 'checked-in') bg-success
                        @elseif(($reservation->status ?? '') === 'pending') bg-secondary
                        @elseif(($reservation->status ?? '') === 'cancelled') bg-danger
                        @elseif(($reservation->status ?? '') === 'confirmed') bg-primary
                        @else bg-warning @endif">
                        {{ ucfirst($reservation->status ?? 'N/A') }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        {{-- View --}}
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewReservationModal{{ $reservation->id }}" title="View Reservation Details">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        {{-- Edit --}}
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editReservationModal{{ $reservation->id }}" title="Edit Reservation">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        {{-- Delete --}}
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteReservationModal{{ $reservation->id }}" title="Delete Reservation">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        {{-- Cancel --}}
                        @if(($reservation->status ?? '') !== 'cancelled' && ($reservation->status ?? '') !== 'checked-out')
                        <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="reason" value="Guest canceled the reservation.">
                            <button type="submit" class="btn btn-warning btn-sm" title="Cancel Reservation">
                                <i class="bi bi-x-circle-fill"></i> Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>

            @include('reservations._modals', ['reservation' => $reservation])
        @empty
            <tr>
                <td colspan="7">
                    <div class="alert alert-info mb-0 text-center">
                        <i class="bi bi-info-circle me-2"></i> No reservations found.
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $reservations->links('pagination::bootstrap-5') }}

    {{-- Create Reservation Modal --}}
    <div class="modal fade" id="createReservationModal" tabindex="-1" aria-labelledby="createReservationLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createReservationLabel">Add New Reservation</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="guestSelect" class="form-label">Guest <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="guest_code" class="form-select" required id="guestSelect">
                                        <option value="">-- Select Guest --</option>
                                        @foreach ($guests as $guest)
                                            <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest">
                                        <i class="bi bi-person-plus-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="roomSelect" class="form-label">Room <span class="text-danger">*</span></label>
                                <select name="room_code" class="form-select" required id="roomSelect">
                                    <option value="">-- Select Room --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_nights" id="number_of_nights" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="checkout_date" class="form-label">Check-out Date</label>
                                <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label for="number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required>
                            </div>

                            <div class="col-12">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" selected>Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="checked-in">Checked In</option>
                                    <option value="checked-out">Checked Out</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Guest Modal --}}
    <div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('guests.store') }}" method="POST" id="addGuestForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addGuestLabel">Add New Guest</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="guestName" class="form-label">Guest Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="guestName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="guestEmail" class="form-label">Email</label>
                            <input type="email" name="email" id="guestEmail" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="guestPhone" class="form-label">Phone</label>
                            <input type="text" name="tel" id="guestPhone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="guestGender" class="form-label">Gender</label>
                            <select name="gender" id="guestGender" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Guest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Toasts
    const successToastEl = document.getElementById('successToast');
    const errorToastEl = document.getElementById('errorToast');
    if (successToastEl) new bootstrap.Toast(successToastEl, { autohide: true, delay: 3000 }).show();
    if (errorToastEl) new bootstrap.Toast(errorToastEl, { autohide: true, delay: 3000 }).show();

    // Auto-calculate checkout date
    function updateCheckoutDate() {
        let checkin = document.getElementById('checkin_date').value;
        let nights = parseInt(document.getElementById('number_of_nights').value) || 1;
        if (checkin) {
            let date = new Date(checkin);
            date.setDate(date.getDate() + nights);
            let y = date.getFullYear();
            let m = ('0' + (date.getMonth()+1)).slice(-2);
            let d = ('0' + date.getDate()).slice(-2);
            let H = ('0' + date.getHours()).slice(-2);
            let I = ('0' + date.getMinutes()).slice(-2);
            document.getElementById('checkout_date').value = `${y}-${m}-${d}T${H}:${I}`;
        }
    }
    document.getElementById('checkin_date').addEventListener('change', updateCheckoutDate);
    document.getElementById('number_of_nights').addEventListener('input', updateCheckoutDate);
    updateCheckoutDate();

    // AJAX form submission for Add Guest Modal
    $('#addGuestForm').submit(function(e) {
        e.preventDefault();

        var $form = $(this);
        var data = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
            success: function(response) {
                if (response && response.guest_code && response.name) {
                    var newOption = new Option(response.name, response.guest_code, true, true);
                    $('#guestSelect').append(newOption).val(response.guest_code);
                    $('#guestSelect').trigger('change');
                    $('#addGuestModal').modal('hide');
                    $form[0].reset(); // Reset the add guest form
                } else {
                    alert('Guest added, but no response data. Please refresh to see the new guest.');
                    $('#addGuestModal').modal('hide');
                }
            },
            error: function(xhr) {
                alert('Failed to add guest. Please check your input.');
            }
        });
    });
});
</script>
@endsection