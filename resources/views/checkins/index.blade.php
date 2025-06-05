@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    @include('partials.toasts')

    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Check-ins</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCheckinModal">Add New</button>
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

    {{-- Check-in Table --}}
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
                    <span class="badge 
                        @if($checkin->is_checkout) bg-secondary 
                        @else bg-success 
                        @endif">
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
                    @php
                        $folio = \App\Models\GuestFolio::where('checkin_code', $checkin->checkin_code)->first();
                    @endphp
                    @if($folio)
                        <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-receipt"></i> Folio
                        </a>
                    @else
                        <form method="POST" action="{{ route('folios.create.for.checkin', $checkin->checkin_code) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Folio
                            </button>
                        </form>
                    @endif
                </td>
            </tr>

            @include('checkins._modals', ['checkin' => $checkin, 'guests' => $guests, 'rooms' => $rooms])
        @empty
            <tr>
                <td colspan="7">
                    <div class="alert alert-info mb-0 text-center">No check-ins found.</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $checkins->links('pagination::bootstrap-5') }}

    {{-- Create Check-in Modal --}}
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
                        {{-- Guest Select with Add New --}}
                        <div class="mb-3">
                            <label class="form-label mb-1">Guest</label>
                            <div class="d-flex">
                                <select name="guest_code" class="form-select me-2 flex-grow-1" required id="guestSelect">
                                    <option value="">-- Select Guest --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" style="white-space:nowrap;">
                                    + Add New Guest
                                </button>
                            </div>
                        </div>
                        {{-- Room Select --}}
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <select name="room_code" class="form-select" required>
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Check-in, Duration, Check-out (calculated) --}}
                        <div class="mb-3">
                            <label class="form-label">Check-in Date</label>
                            <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Nights</label>
                            <input type="number" name="number_of_nights" id="number_of_nights" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Check-out Date</label>
                            <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" readonly required>
                        </div>
                        {{-- Number of Guests --}}
                        <div class="mb-3">
                            <label class="form-label">Number of Guests</label>
                            <input type="number" name="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                        {{-- Rate --}}
                        <div class="mb-3">
                            <label class="form-label">Rate</label>
                            <input type="number" step="0.01" name="rate" class="form-control" value="0">
                        </div>
                        {{-- Total Payment --}}
                        <div class="mb-3">
                            <label class="form-label">Total Payment</label>
                            <input type="number" step="0.01" name="total_payment" class="form-control" value="0">
                        </div>
                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <input type="text" name="payment_method" class="form-control">
                        </div>
                        {{-- Mark as Checked Out --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_checkout" value="1">
                            <label class="form-check-label">Mark as Checked Out</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create</button>
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
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGuestLabel">Add New Guest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Guest Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="tel" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
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

    // Auto-calculate checkout date (just like reservation)
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

    // AJAX Guest Add
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
                    $form[0].reset();
                } else {
                    alert('Guest added, but no response data.');
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
