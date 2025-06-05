@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    @include('partials.toasts')

    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Check-ins</h3> {{-- Added text-primary for consistency --}}
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCheckinModal">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Check-in
        </button>
    </div>

    {{-- Filter/Search Form (NOW SAME UI AS RESERVATION MANAGEMENT) --}}
    <div class="card shadow-sm mb-4"> {{-- Added card component --}}
        <div class="card-body">
            <form method="GET" action="{{ route('checkins.index') }}" class="row g-3 align-items-end"> {{-- Changed g-2 to g-3 for consistent spacing, added align-items-end --}}
                <div class="col-md-4">
                    <label for="filterGuestName" class="form-label">Guest Name</label> {{-- Added label --}}
                    <input type="text" name="guest" id="filterGuestName" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
                </div>
                <div class="col-md-4">
                    <label for="filterCheckinDate" class="form-label">Check-in Date</label> {{-- Added label --}}
                    <input type="date" name="checkin_date" id="filterCheckinDate" value="{{ request('checkin_date') }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex gap-2"> {{-- Adjusted for buttons on same line --}}
                    <button type="submit" class="btn btn-primary flex-grow-1"> {{-- Changed w-100 to flex-grow-1 --}}
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary flex-grow-1"> {{-- Changed w-100 to flex-grow-1 --}}
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    {{-- END Filter/Search Form --}}


    {{-- Check-in Table --}}
    <table class="table table-bordered table-hover table-striped"> {{-- Added table-striped for consistency --}}
        <thead>
            <tr>
                <th>ID</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th class="text-center">Actions</th> {{-- Added text-center for consistency --}}
            </tr>
        </thead>
        <tbody>
        @forelse($checkins as $checkin)
            <tr>
                <td>{{ $checkin->id }}</td>
                <td>{{ $checkin->guest->name ?? 'N/A' }}</td>
                <td>{{ $checkin->room->name ?? 'N/A' }}</td>
                <td>{{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y H:i') : 'N/A' }}</td> {{-- Formatted date --}}
                <td>{{ $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date)->format('M d, Y H:i') : 'N/A' }}</td> {{-- Formatted date --}}
                <td>
                    <span class="badge text-white
                        @if($checkin->is_checkout) bg-secondary
                        @else bg-success
                        @endif">
                        {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                    </span>
                </td>
                <td class="text-center"> {{-- Added text-center for consistency --}}
                    <div class="d-flex justify-content-center gap-1"> {{-- Added gap-1 for consistency --}}
                        {{-- View --}}
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewCheckinModal{{ $checkin->id }}" title="View Check-in Details"> {{-- Added title --}}
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        {{-- Edit --}}
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editCheckinModal{{ $checkin->id }}" title="Edit Check-in"> {{-- Added title --}}
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        {{-- Delete --}}
                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCheckinModal{{ $checkin->id }}" title="Delete Check-in"> {{-- Added title --}}
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        @php
                            $folio = \App\Models\GuestFolio::where('checkin_code', $checkin->checkin_code)->first();
                        @endphp
                        @if($folio)
                            <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-dark btn-sm" title="View Folio"> {{-- Added title --}}
                                <i class="bi bi-receipt"></i> Folio
                            </a>
                        @else
                            <form method="POST" action="{{ route('folios.create.for.checkin', $checkin->checkin_code) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm" title="Create Folio"> {{-- Added title --}}
                                    <i class="bi bi-plus-circle"></i> Folio
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>

            @include('checkins._modals', ['checkin' => $checkin, 'guests' => $guests, 'rooms' => $rooms])
        @empty
            <tr>
                <td colspan="7">
                    <div class="alert alert-info mb-0 text-center">
                        <i class="bi bi-info-circle me-2"></i> No check-ins found. {{-- Added icon --}}
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $checkins->links('pagination::bootstrap-5') }}

    {{-- Create Check-in Modal --}}
    <div class="modal fade" id="createCheckinModal" tabindex="-1" aria-labelledby="createCheckinLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> {{-- Changed custom-modal to modal-lg for wider modal --}}
            <div class="modal-content">
                <form action="{{ route('checkins.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCheckinLabel">Add New Check-in</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3"> {{-- Applied row g-3 for consistent grid layout --}}
                            {{-- Guest Select with Add New --}}
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="guestSelect" class="form-label">Guest <span class="text-danger">*</span></label> {{-- Added for attribute to label --}}
                                <div class="input-group"> {{-- Used input-group for button next to select --}}
                                    <select name="guest_code" class="form-select" required id="guestSelect">
                                        <option value="">-- Select Guest --</option>
                                        @foreach ($guests as $guest)
                                            <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal" title="Add New Guest"> {{-- Changed button text to icon + text, removed inline style --}}
                                        <i class="bi bi-person-plus-fill"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- Room Select --}}
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="roomSelect" class="form-label">Room <span class="text-danger">*</span></label> {{-- Added for attribute to label --}}
                                <select name="room_code" class="form-select" required id="roomSelect"> {{-- Added id --}}
                                    <option value="">-- Select Room --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Check-in, Duration, Check-out (calculated) --}}
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label> {{-- Added for attribute to label --}}
                                <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" required>
                            </div>
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="number_of_nights" class="form-label">Number of Nights <span class="text-danger">*</span></label> {{-- Added for attribute to label --}}
                                <input type="number" name="number_of_nights" id="number_of_nights" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="checkout_date" class="form-label">Check-out Date</label> {{-- Added for attribute to label --}}
                                <input type="datetime-local" name="checkout_date" id="checkout_date" class="form-control" readonly required>
                            </div>
                            {{-- Number of Guests --}}
                            <div class="col-md-6"> {{-- Used col-md-6 for two-column layout --}}
                                <label for="number_of_guest" class="form-label">Number of Guests <span class="text-danger">*</span></label> {{-- Added for attribute to label --}}
                                <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required> {{-- Added id --}}
                            </div>
                            
                            {{-- <div class="col-md-6">
                                <label for="rate" class="form-label">Rate</label> 
                                <input type="number" step="0.01" name="rate" id="rate" class="form-control" value="0">
                            </div>
                             --}}
                            {{-- <div class="col-md-6">
                                <label for="total_payment" class="form-label">Total Payment</label> 
                                <input type="number" step="0.01" name="total_payment" id="total_payment" class="form-control" value="0">                            </div>
                            <div class="col-md-6"> 
                                <label for="payment_method" class="form-label">Payment Method</label> 
                                <input type="text" name="payment_method" id="payment_method" class="form-control"> 
                            </div> --}}

                            {{-- Mark as Checked Out --}}
                            <div class="col-12"> {{-- Kept as col-12 as it's a single checkbox spanning full width --}}
                                <div class="form-check"> {{-- Changed mb-3 to be inside col for better spacing control --}}
                                    <input class="form-check-input" type="checkbox" name="is_checkout" id="is_checkout" value="1"> {{-- Added id --}}
                                    <label class="form-check-label" for="is_checkout">Mark as Checked Out</label> {{-- Added for attribute to label --}}
                                </div>
                            </div>
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

    {{-- Add Guest Modal (already has good, standard modal form UI) --}}
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
                            <label for="guestName" class="form-label">Guest Name <span class="text-danger">*</span></label> {{-- Added for attribute --}}
                            <input type="text" name="name" id="guestName" class="form-control" required> {{-- Added id --}}
                        </div>
                        <div class="mb-3">
                            <label for="guestEmail" class="form-label">Email</label> {{-- Added for attribute --}}
                            <input type="email" name="email" id="guestEmail" class="form-control"> {{-- Added id --}}
                        </div>
                        <div class="mb-3">
                            <label for="guestPhone" class="form-label">Phone</label> {{-- Added for attribute --}}
                            <input type="text" name="tel" id="guestPhone" class="form-control"> {{-- Added id --}}
                        </div>
                        <div class="mb-3">
                            <label for="guestGender" class="form-label">Gender</label> {{-- Added for attribute --}}
                            <select name="gender" id="guestGender" class="form-select"> {{-- Added id --}}
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
    // Initial call in case values are pre-filled (e.g., validation error)
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
                    // Consider a more robust error message or toast notification here
                    alert('Guest added, but no response data. Please refresh to see the new guest if not automatically selected.');
                    $('#addGuestModal').modal('hide');
                }
            },
            error: function(xhr) {
                // More detailed error handling could be added here
                alert('Failed to add guest. Please check your input or console for details.');
                console.error('AJAX error:', xhr.responseText);
            }
        });
    });
});
</script>
@endsection