@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    @include('partials.toasts')

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Bills / Statements</h3> {{-- Changed Folios to Bills/Statements --}}
        {{-- If you need a button to create a new, empty bill (not tied to a check-in), uncomment this:
        <a href="{{ route('folios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> New Bill
        </a>
        --}}
    </div>

    {{-- Filter/Search Form (Consistent UI) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('folios.index') }}" class="row g-3 align-items-end"> {{-- Changed g-2 to g-3, added align-items-end --}}
                <div class="col-md-4">
                    <label for="filterGuest" class="form-label">Guest Name</label> {{-- Added label --}}
                    <input type="text" name="guest" id="filterGuest" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
                </div>
                <div class="col-md-4">
                    <label for="filterRoom" class="form-label">Room Number</label> {{-- Added label --}}
                    <input type="text" name="room" id="filterRoom" value="{{ request('room') }}" class="form-control" placeholder="Search by Room Number">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('folios.index') }}" class="btn btn-outline-secondary flex-grow-1">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    {{-- END Filter/Search Form --}}

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle table-striped"> {{-- Added table-striped --}}
            <thead class="table-primary"> {{-- Changed table-light to table-primary for consistent header color --}}
                <tr>
                    <th>Bill #</th> {{-- Changed Folio # to Bill # --}}
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Stay Period</th>
                    <th class="text-center">Status</th> {{-- Aligned center for badge --}}
                    <th class="text-end">Total (USD)</th> {{-- Aligned right for currency --}}
                    <th class="text-center">Actions</th> {{-- Aligned center for buttons --}}
                </tr>
            </thead>
            <tbody>
            @forelse($folios as $folio)
                <tr>
                    <td>
                        <span class="fw-bold text-primary">{{ $folio->folio_code ?? $folio->id }}</span> {{-- Added text-primary --}}
                        <br>
                        <small class="text-muted">{{ $folio->created_at->format('d M Y') }}</small>
                    </td>
                    <td>
                        {{ $folio->guest->name ?? 'N/A' }}
                        <br>
                        <span class="badge bg-light text-secondary border"> {{-- Added border for subtle distinction --}}
                            {{ $folio->guest->email ?? $folio->guest->tel ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $folio->room->name ?? 'N/A' }}</td>
                    <td>
                        {{ $folio->checkin?->checkin_date ? \Carbon\Carbon::parse($folio->checkin->checkin_date)->format('M d, Y H:i') : '-' }}<br>
                        <span class="text-muted">to</span><br>
                        {{ $folio->checkin?->checkout_date ? \Carbon\Carbon::parse($folio->checkin->checkout_date)->format('M d, Y H:i') : '-' }}
                    </td>
                    <td class="text-center"> {{-- Aligned center --}}
                        <span class="badge text-white
                            @if($folio->status === 'paid') bg-success
                            @elseif($folio->status === 'partial') bg-warning
                            @elseif($folio->status === 'voided') bg-danger
                            @else bg-secondary @endif">
                            {{ ucfirst($folio->status) }}
                        </span>
                    </td>
                    <td class="fw-bold text-end text-success"> {{-- Aligned right, added text-success for total --}}
                        {{ number_format($folio->total_amount, 2) }}
                    </td>
                    <td class="text-center"> {{-- Aligned center for button group --}}
                        <div class="d-flex justify-content-center gap-1"> {{-- Added gap-1 for consistent spacing --}}
                            <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-primary btn-sm" title="View Bill Details"> {{-- Changed btn-outline-dark to btn-primary, added title --}}
                                <i class="bi bi-eye-fill me-1"></i> View
                            </a>
                            <a href="{{ route('folios.print', $folio->folio_code) }}" class="btn btn-secondary btn-sm" target="_blank" title="Print Bill"> {{-- Changed btn-outline-secondary to btn-secondary, added title --}}
                                <i class="bi bi-printer"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteFolioModal{{ $folio->id }}" title="Delete Bill"> {{-- Added title --}}
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="alert alert-info mb-0 text-center">
                            <i class="bi bi-info-circle me-2"></i> No guest bills found. {{-- Updated message and added icon --}}
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination links --}}
    <div class="d-flex justify-content-center mt-3"> {{-- Centered pagination --}}
        {{ $folios->links('pagination::bootstrap-5') }}
    </div>


    {{-- Delete Bill Modals --}}
    @foreach($folios as $folio)
        <div class="modal fade" id="deleteFolioModal{{ $folio->id }}" tabindex="-1" aria-labelledby="deleteFolioLabel{{ $folio->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm"> {{-- Changed modal-md to modal-sm for delete confirmation --}}
                <div class="modal-content">
                    <form action="{{ route('folios.destroy', $folio->folio_code) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header bg-danger text-white"> {{-- Added bg-danger --}}
                            <h5 class="modal-title" id="deleteFolioLabel{{ $folio->id }}">Confirm Deletion</h5> {{-- Changed title --}}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> {{-- Added btn-close-white --}}
                        </div>
                        <div class="modal-body">
                            <p class="text-center fs-5">
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Are you sure?
                            </p>
                            <p class="text-center">
                                You are about to delete bill
                                <strong>#{{ $folio->folio_code ?? $folio->id }}</strong>
                                for guest <strong>{{ $folio->guest->name ?? 'N/A' }}</strong>.
                            </p>
                            <p class="text-center text-danger fw-bold">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer justify-content-center"> {{-- Centered buttons --}}
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete Bill</button> {{-- Updated button text --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Bootstrap Toasts
    const successToastEl = document.getElementById('successToast');
    const errorToastEl = document.getElementById('errorToast');
    if (successToastEl) new bootstrap.Toast(successToastEl, { autohide: true, delay: 3000 }).show();
    if (errorToastEl) new bootstrap.Toast(errorToastEl, { autohide: true, delay: 3000 }).show();

    // Optional: Confirm for dangerous actions (e.g., void folio)
    // Note: The delete modal already handles confirmation. If you had a separate 'void' button,
    // this script would be useful for it. For now, it's commented out/not strictly needed
    // if only delete uses a modal.
    /*
    $('.btn-void-folio').on('click', function(e) {
        if(!confirm('Are you sure you want to void this bill? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
    */

    // Add more scripts for bill actions here if needed
});
</script>
@endsection