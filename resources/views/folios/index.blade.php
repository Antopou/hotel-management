@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">

    {{-- Toast Notifications --}}
    @include('partials.toasts')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Folios / Billing</h3>
        {{-- <a href="{{ route('folios.create') }}" class="btn btn-success">
            <i class="bi bi-plus"></i> New Folio
        </a> --}}
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('folios.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="guest" value="{{ request('guest') }}" class="form-control" placeholder="Search by Guest Name">
        </div>
        <div class="col-md-4">
            <input type="text" name="room" value="{{ request('room') }}" class="form-control" placeholder="Search by Room">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('folios.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Folio #</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Stay Period</th>
                    <th>Status</th>
                    <th>Total (USD)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($folios as $folio)
                <tr>
                    <td>
                        <span class="fw-bold">{{ $folio->folio_code ?? $folio->id }}</span>
                        <br>
                        <small class="text-muted">{{ $folio->created_at->format('d M Y') }}</small>
                    </td>
                    <td>
                        {{ $folio->guest->name ?? 'N/A' }}
                        <br>
                        <span class="badge bg-light text-secondary">
                            {{ $folio->guest->email ?? $folio->guest->tel ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $folio->room->name ?? 'N/A' }}</td>
                    <td>
                        {{ $folio->checkin?->checkin_date ? \Carbon\Carbon::parse($folio->checkin->checkin_date)->format('d M Y H:i') : '-' }}<br>
                        <span class="text-muted">to</span><br>
                        {{ $folio->checkin?->checkout_date ? \Carbon\Carbon::parse($folio->checkin->checkout_date)->format('d M Y H:i') : '-' }}
                    </td>
                    <td>
                        <span class="badge
                            @if($folio->status === 'paid') bg-success
                            @elseif($folio->status === 'partial') bg-warning
                            @elseif($folio->status === 'voided') bg-danger
                            @else bg-secondary @endif">
                            {{ ucfirst($folio->status) }}
                        </span>
                    </td>
                    <td class="fw-bold text-end">
                        {{ number_format($folio->total_amount, 2) }}
                    </td>
                    <td>
                        <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-outline-dark btn-sm me-1">
                            <i class="bi bi-receipt"></i> View
                        </a>
                        <a href="{{ route('folios.print', $folio->folio_code) }}" class="btn btn-outline-secondary btn-sm me-1" target="_blank" title="Print">
                            <i class="bi bi-printer"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteFolioModal{{ $folio->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="alert alert-info mb-0 text-center">No folios found.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete Folio Modals --}}
    @foreach($folios as $folio)
        <!-- Delete Folio Modal -->
        <div class="modal fade" id="deleteFolioModal{{ $folio->id }}" tabindex="-1" aria-labelledby="deleteFolioLabel{{ $folio->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form action="{{ route('folios.destroy', $folio->folio_code) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteFolioLabel{{ $folio->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Are you sure you want to delete folio
                                <strong>#{{ $folio->folio_code ?? $folio->id }}</strong>
                                for guest <strong>{{ $folio->guest->name ?? 'N/A' }}</strong>?
                            </p>
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
    @endforeach

    {{ $folios->links('pagination::bootstrap-5') }}
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
    $('.btn-void-folio').on('click', function(e) {
        if(!confirm('Are you sure you want to void this folio? This action cannot be undone.')) {
            e.preventDefault();
        }
    });

    // Add more scripts for folio actions here if needed
});
</script>
@endsection
