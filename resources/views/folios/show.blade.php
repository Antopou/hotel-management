@extends('layouts.main')

@section('content')
<div class="container py-4">

    {{-- Page Title and Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-dark"> {{-- Changed to text-primary --}}
            <i class="bi bi-receipt me-2"></i>
            Bill #<span class="fw-bold">{{ $folio->folio_code ?? $folio->id }}</span> {{-- Changed to Bill # --}}
        </h3>
        <div>
            <a href="{{ route('folios.index') }}" class="btn btn-outline-secondary me-2"> {{-- Changed btn-light to btn-outline-secondary --}}
                <i class="bi bi-arrow-left me-1"></i> Back to Bills List {{-- Updated text and added icon --}}
            </a>
            <a href="{{ route('folios.print', $folio->folio_code ?? $folio->id) }}" class="btn btn-primary" target="_blank"> {{-- Changed btn-dark to btn-primary --}}
                <i class="bi bi-printer me-1"></i> Print Bill {{-- Updated text and added icon --}}
            </a>
        </div>
    </div>

    {{-- Bill Summary Card --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white"> {{-- Added colored header --}}
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Bill Details</h5> {{-- Added title and icon --}}
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Guest Name</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->guest->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Room</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->room->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Bill Date</dt> {{-- Changed Folio Date to Bill Date --}}
                <dd class="col-md-9 fw-bold">{{ $folio->created_at ? \Carbon\Carbon::parse($folio->created_at)->format('M d, Y H:i') : '-' }}</dd> {{-- Formatted date --}}

                <dt class="col-md-3 text-muted">Linked Check-in</dt> {{-- Changed Linked Reservation to Linked Check-in for accuracy --}}
                <dd class="col-md-9">
                    @if($folio->checkin)
                        <a href="{{ route('checkins.index', ['checkin_code' => $folio->checkin->checkin_code]) }}" class="fw-bold text-primary"> {{-- Link to Check-ins page --}}
                            <i class="bi bi-link-45deg me-1"></i> #{{ $folio->checkin->checkin_code ?? $folio->checkin->id }}
                        </a>
                        <small class="text-muted ms-2">
                            ({{ \Carbon\Carbon::parse($folio->checkin->checkin_date)->format('M d') }} -
                            {{ \Carbon\Carbon::parse($folio->checkin->checkout_date)->format('M d') }})
                        </small>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Charges Table --}}
    <div class="card shadow-sm mb-3">
        <div class="card-header text-black d-flex justify-content-between align-items-center"> {{-- Added colored header --}}
            <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i> Charges</h5> {{-- Added title and icon --}}
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover table-striped"> {{-- Added table-striped --}}
                <thead class="table"> {{-- Changed table-bordered to table-dark --}}
                    <tr>
                        <th style="width: 15%;">Date</th> {{-- Added width --}}
                        <th>Description</th>
                        <th class="text-end" style="width: 15%;">Amount (USD)</th> {{-- Added width and currency --}}
                    </tr>
                </thead>
                <tbody>
                @forelse($items->where('type', 'charge') as $charge)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($charge->posted_at)->format('M d, Y') }}</td>
                        <td>
                            {{ $charge->description }}
                            @if($charge->description === 'Room Charge' && $folio->checkin) {{-- Check if checkin exists on folio --}}
                                <br>
                                <small class="text-muted">
                                    {{ $folio->checkin->room->roomType->name ?? '' }}:
                                    {{ number_format($folio->checkin->room->roomType->price_per_night ?? 0, 2) }} ×
                                    {{ \Carbon\Carbon::parse($folio->checkin->checkin_date)->diffInDays(\Carbon\Carbon::parse($folio->checkin->checkout_date)) }} night(s)
                                </small>
                            @endif
                        </td>
                        <td class="text-end fw-bold">{{ number_format($charge->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info mb-0 text-center">
                                <i class="bi bi-info-circle me-2"></i> No charges recorded for this bill.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header text-black d-flex justify-content-between align-items-center #"> {{-- Added colored header --}}
            <h5 class="mb-0"><i class="bi bi-wallet-fill me-2"></i> Payments</h5> {{-- Added title and icon --}}
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPaymentModal"> {{-- Changed btn-primary to btn-light --}}
                <i class="bi bi-plus-circle-fill me-1"></i> Add Payment
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover table-striped"> {{-- Added table-striped --}}
                <thead class="table"> {{-- Changed table-bordered to table-dark --}}
                    <tr>
                        <th style="width: 15%;">Date</th> {{-- Added width --}}
                        <th>Reference/Method</th> {{-- Improved header text --}}
                        <th class="text-end" style="width: 15%;">Amount (USD)</th> {{-- Added width and currency --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($items->where('type', 'payment') as $payment)
                        <tr>
                            <td>{{ $payment->posted_at ? \Carbon\Carbon::parse($payment->posted_at)->format('M d, Y') : '-' }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td class="text-end fw-bold">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="alert alert-info mb-0 text-center">
                                    <i class="bi bi-info-circle me-2"></i> No payments recorded for this bill.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totals Summary --}}
    @php
        $totalCharges = $items->where('type', 'charge')->sum('amount');
        $totalPayments = $items->where('type', 'payment')->sum('amount');
        $balance = $totalCharges - $totalPayments;
        $balanceClass = $balance > 0 ? 'text-danger' : ($balance < 0 ? 'text-success' : 'text-primary');
    @endphp
    <div class="card shadow-sm p-3"> {{-- Added card for totals --}}
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <table class="table table-borderless mb-0"> {{-- Removed table-borderless if you want lines --}}
                    <tr>
                        <th class="text-end fs-6">Total Charges:</th>
                        <td class="text-end fs-6 fw-bold text-info">{{ number_format($totalCharges, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-end fs-6">Total Payments:</th>
                        <td class="text-end fs-6 fw-bold text-success">{{ number_format($totalPayments, 2) }}</td>
                    </tr>
                    <tr class="border-top border-secondary"> {{-- Added border to separate totals from balance --}}
                        <th class="text-end fs-5">Balance Due:</th>
                        <td class="text-end fs-5 fw-bolder {{ $balanceClass }}">{{ number_format($balance, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog custom-modal">
        <form action="{{ route('folios.items.store', $folio->folio_code) }}" method="POST">
          @csrf
          <div class="modal-content">
            <div class="modal-header bg-success text-white"> {{-- Added colored header --}}
              <h5 class="modal-title" id="addPaymentModalLabel"><i class="bi bi-plus-circle-fill me-2"></i> Add New Payment</h5> {{-- Added icon --}}
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> {{-- Added btn-close-white --}}
            </div>
            <div class="modal-body">
              <input type="hidden" name="type" value="payment">
              <div class="mb-3">
                <label for="paymentAmount" class="form-label">Amount (USD) <span class="text-danger">*</span></label> {{-- Added for, id, currency, required indicator --}}
                <input type="number" step="0.01" name="amount" id="paymentAmount" class="form-control" required min="0.01">
              </div>
              <div class="mb-3">
                <label for="paymentReference" class="form-label">Reference/Method</label> {{-- Added for, id --}}
                <input type="text" name="reference" id="paymentReference" class="form-control" placeholder="Cash, Card, Bank Transfer, etc.">
              </div>
              <div class="mb-3">
                <label for="paymentDescription" class="form-label">Description <span class="text-danger">*</span></label> {{-- Added for, id, required indicator --}}
                <input type="text" name="description" id="paymentDescription" class="form-control" value="Payment" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Add Payment</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- No specific JS needed for this view unless you add dynamic features --}}
@endsection