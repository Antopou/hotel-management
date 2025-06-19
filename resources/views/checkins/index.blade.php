@extends('layouts.main')

@section('title', 'Check-ins - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Check-ins</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Guest Check-ins</h1>
        <p class="page-subtitle">Manage guest check-ins and check-outs</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCheckinModal">
        <i class="bi bi-plus-circle me-2"></i>
        New Check-in
    </button>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('checkins.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="filterGuestName" class="form-label">Guest Name</label>
                <input type="text" name="guest" id="filterGuestName" value="{{ request('guest') }}" 
                       class="form-control" placeholder="Search by guest name">
            </div>
            <div class="col-md-4">
                <label for="filterCheckinDate" class="form-label">Check-in Date</label>
                <input type="date" name="checkin_date" id="filterCheckinDate" value="{{ request('checkin_date') }}" 
                       class="form-control">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Check-ins Table -->
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
                        <th>Status</th>
                        <th>Guests</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($checkins as $checkin)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $checkin->guest->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $checkin->guest->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $checkin->room->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y') : 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('H:i') : '' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date)->format('M d, Y') : 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date)->format('H:i') : '' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}">
                                {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $checkin->number_of_guest }} {{ Str::plural('Guest', $checkin->number_of_guest) }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewCheckinModal{{ $checkin->id }}" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCheckinModal{{ $checkin->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCheckinModal{{ $checkin->id }}" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @php
                                    $folio = \App\Models\GuestFolio::where('checkin_code', $checkin->checkin_code)->first();
                                @endphp
                                @if($folio)
                                    <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-outline-primary" title="View Folio">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('folios.create.for.checkin', $checkin->checkin_code) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Create Folio">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @include('checkins._modals', ['checkin' => $checkin, 'guests' => $guests, 'rooms' => $rooms])
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-muted"></i>
                                <h5>No check-ins found</h5>
                                <p>Start by creating your first check-in</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCheckinModal">
                                    <i class="bi bi-plus-circle me-2"></i>Create Check-in
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
@if($checkins->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $checkins->links('pagination::bootstrap-5') }}
</div>
@endif

@include('checkins._modal_create')
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
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
    
    if (document.getElementById('checkin_date')) {
        document.getElementById('checkin_date').addEventListener('change', updateCheckoutDate);
        document.getElementById('number_of_nights').addEventListener('input', updateCheckoutDate);
        updateCheckoutDate();
    }

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
                    alert('Guest added successfully!');
                    $('#addGuestModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Failed to add guest. Please try again.');
                console.error('AJAX error:', xhr.responseText);
            }
        });
    });
});
</script>
@endpush
