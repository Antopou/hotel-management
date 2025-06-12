@extends('layouts.main-nosidebar')

@section('content')
<div class="container py-4">

    {{-- Page Title and Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-dark">
            <i class="bi bi-receipt me-2"></i>
            Bill #<span class="fw-bold">{{ $folio->folio_code ?? $folio->id }}</span>
        </h3>
        <div>
            <a href="{{ route('frontdesk.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('folios.print', $folio->folio_code ?? $folio->id) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-printer me-1"></i> Print Bill
            </a>
            <button type="button" class="btn btn-success ms-2" id="confirmBillBtn">
                <i class="bi bi-check-circle me-1"></i> Confirm
            </button>
        </div>
    </div>

    {{-- Bill Summary Card --}}
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Bill Details</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3 text-muted">Guest Name</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->guest->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Room</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->room->name ?? '-' }}</dd>

                <dt class="col-md-3 text-muted">Bill Date</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->created_at ? \Carbon\Carbon::parse($folio->created_at)->format('M d, Y H:i') : '-' }}</dd>

                <dt class="col-md-3 text-muted">Linked Check-in</dt>
                <dd class="col-md-9">
                    @if($folio->checkin)
                        <a href="{{ route('checkins.index', ['checkin_code' => $folio->checkin->checkin_code]) }}" class="fw-bold text-primary">
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
        <div class="card-header text-black d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i> Charges</h5>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover table-striped">
                <thead class="table">
                    <tr>
                        <th style="width: 15%;">Date</th>
                        <th>Description</th>
                        <th class="text-end" style="width: 15%;">Amount (USD)</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items->where('type', 'charge') as $charge)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($charge->posted_at)->format('M d, Y') }}</td>
                        <td>
                            {{ $charge->description }}
                            @if($charge->description === 'Room Charge' && $folio->checkin)
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
        <div class="card-header text-black d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-wallet-fill me-2"></i> Payments</h5>
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                <i class="bi bi-plus-circle-fill me-1"></i> Add Payment
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover table-striped">
                <thead class="table">
                    <tr>
                        <th style="width: 15%;">Date</th>
                        <th>Reference/Method</th>
                        <th class="text-end" style="width: 15%;">Amount (USD)</th>
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
    <div class="card shadow-sm p-3">
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-end fs-6">Total Charges:</th>
                        <td class="text-end fs-6 fw-bold text-info">{{ number_format($totalCharges, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-end fs-6">Total Payments:</th>
                        <td class="text-end fs-6 fw-bold text-success">{{ number_format($totalPayments, 2) }}</td>
                    </tr>
                    <tr class="border-top border-secondary">
                        <th class="text-end fs-5">Balance Due:</th>
                        <td class="text-end fs-5 fw-bolder {{ $balanceClass }}">{{ number_format($balance, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if($balance < 0)
        <div class="alert alert-success mt-3">
            <i class="bi bi-cash-coin me-2"></i>
            Change to return: <strong>${{ number_format(abs($balance), 2) }}</strong>
        </div>
    @elseif($balance > 0)
        <div class="alert alert-warning mt-3">
            <i class="bi bi-exclamation-circle me-2"></i>
            Amount due: <strong>${{ number_format($balance, 2) }}</strong>
        </div>
    @else
        <div class="alert alert-info mt-3">
            <i class="bi bi-check-circle me-2"></i>
            Bill settled. No balance due.
        </div>
    @endif

    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog custom-modal">
        <form action="{{ route('folios.items.store', $folio->folio_code) }}" method="POST">
          @csrf
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="addPaymentModalLabel"><i class="bi bi-plus-circle-fill me-2"></i> Add New Payment</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="type" value="payment">
              <div class="mb-3">
                <label for="paymentAmount" class="form-label">Amount (USD) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" id="paymentAmount" class="form-control" required min="0.01">
              </div>
              <div class="mb-3">
                <label for="paymentReference" class="form-label">Reference/Method</label>
                <input type="text" name="reference" id="paymentReference" class="form-control" placeholder="Cash, Card, Bank Transfer, etc.">
              </div>
              <div class="mb-3">
                <label for="paymentDescription" class="form-label">Description <span class="text-danger">*</span></label>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('confirmBillBtn');
    if (btn) {
        btn.addEventListener('click', function() {
            window.location.href = "{{ route('frontdesk.index') }}";
        });
    }
});
</script>
@endpush
