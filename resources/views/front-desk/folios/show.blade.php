@extends('layouts.main-nosidebar')

@section('content')
{{-- Fullscreen Loader --}}
<style>
    #fullscreen-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        transition: opacity 0.5s;
    }
    #fullscreen-loader.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .spinner-border {
        width: 4rem;
        height: 4rem;
        color: #0d6efd;
    }
    
    /* Enhanced Folio Styling */
    body {
        font-size: 16px;
        line-height: 1.6;
        background: #f8f9fa;
    }
    
    .container {
        max-width: 1200px;
    }
    
    /* Enhanced page header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    }
    .page-header h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Enhanced cards */
    .card {
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: white;
        margin-bottom: 2rem;
    }
    .card-header {
        border: none;
        border-radius: 1.2rem 1.2rem 0 0;
        padding: 1.5rem 2rem;
        font-weight: 600;
    }
    .card-body {
        padding: 2rem;
    }
    .card-header h5 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    /* Enhanced tables */
    .table {
        font-size: 1rem;
        margin-bottom: 0;
    }
    .table th {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        background: #f8f9fa;
        border: none;
        padding: 1.2rem;
    }
    .table td {
        padding: 1.2rem;
        vertical-align: middle;
        border-color: #f1f3f4;
    }
    .table-hover tbody tr:hover {
        background: #f8fafc;
    }
    .table-bordered {
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background: #f8f9fa;
    }
    
    /* Enhanced buttons */
    .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-sm {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    /* Enhanced alerts */
    .alert {
        font-size: 1rem;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        border: none;
        font-weight: 500;
    }
    
    /* Enhanced badges */
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    /* Enhanced description lists */
    dl.row dt {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        padding: 0.5rem 0;
    }
    dl.row dd {
        font-size: 1rem;
        color: #212529;
        padding: 0.5rem 0;
    }
    
    /* Enhanced totals section */
    .totals-table {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1.5rem;
    }
    .totals-table .table {
        margin-bottom: 0;
        background: transparent;
    }
    .totals-table th,
    .totals-table td {
        border: none;
        padding: 0.75rem 0;
        background: transparent;
    }
    .totals-table th {
        font-size: 1.1rem;
    }
    .totals-table td {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    /* Enhanced modal */
    .modal-dialog.custom-modal {
        max-width: 600px;
    }
    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    .modal-header {
        border: none;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem 2rem;
    }
    .modal-body {
        padding: 2rem;
    }
    .modal-footer {
        border: none;
        padding: 1.5rem 2rem;
    }
    
    /* Form enhancements */
    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .form-control {
        font-size: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
<div id="fullscreen-loader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
{{-- End Loader --}}

<div class="container py-4">

    {{-- Page Title and Action Buttons --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-receipt me-3"></i>
                Bill #<span class="fw-bold">{{ $folio->folio_code ?? $folio->id }}</span>
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('front-desk.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
                <a href="{{ route('folios.print', $folio->folio_code ?? $folio->id) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer me-2"></i>Print Bill
                </a>
                <button type="button" class="btn btn-success" id="confirmBillBtn">
                    <i class="bi bi-check-circle me-2"></i>Confirm
                </button>
            </div>
        </div>
    </div>

    {{-- Bill Summary Card --}}
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Bill Details</h5>
        </div>
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Guest Name</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->guest->name ?? '-' }}</dd>

                <dt class="col-md-3">Room</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->room->name ?? '-' }}</dd>

                <dt class="col-md-3">Bill Date</dt>
                <dd class="col-md-9 fw-bold">{{ $folio->created_at ? \Carbon\Carbon::parse($folio->created_at)->format('M d, Y H:i') : '-' }}</dd>

                <dt class="col-md-3">Linked Check-in</dt>
                <dd class="col-md-9">
                    @if($folio->checkin)
                        <a href="{{ route('checkins.index', ['checkin_code' => $folio->checkin->checkin_code]) }}" class="fw-bold text-primary">
                            <i class="bi bi-link-45deg me-1"></i>#{{ $folio->checkin->checkin_code ?? $folio->checkin->id }}
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
    <div class="card shadow-sm">
        <div class="card-header text-black d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Charges</h5>
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
                            <strong>{{ $charge->description }}</strong>
                            @if($charge->description === 'Room Charge' && $folio->checkin)
                                <br>
                                <small class="text-muted">
                                    {{ $folio->checkin->room->roomType->name ?? '' }}:
                                    ${{ number_format($folio->checkin->room->roomType->price_per_night ?? 0, 2) }} ×
                                    {{ \Carbon\Carbon::parse($folio->checkin->checkin_date)->diffInDays(\Carbon\Carbon::parse($folio->checkin->checkout_date)) }} night(s)
                                </small>
                            @endif
                        </td>
                        <td class="text-end fw-bold">${{ number_format($charge->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-info mb-0 text-center">
                                <i class="bi bi-info-circle me-2"></i>No charges recorded for this bill.
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card shadow-sm">
        <div class="card-header text-black d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-wallet-fill me-2"></i>Payments</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                <i class="bi bi-plus-circle-fill me-2"></i>Add Payment
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
                            <td><strong>{{ $payment->reference ?? '-' }}</strong></td>
                            <td class="text-end fw-bold">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="alert alert-info mb-0 text-center">
                                    <i class="bi bi-info-circle me-2"></i>No payments recorded for this bill.
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
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 offset-lg-6">
                    <div class="totals-table">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="text-end">Total Charges:</th>
                                <td class="text-end text-info">${{ number_format($totalCharges, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Total Payments:</th>
                                <td class="text-end text-success">${{ number_format($totalPayments, 2) }}</td>
                            </tr>
                            <tr class="border-top border-secondary">
                                <th class="text-end fs-4">Balance Due:</th>
                                <td class="text-end fs-4 fw-bolder {{ $balanceClass }}">${{ number_format($balance, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($balance < 0)
        <div class="alert alert-success">
            <i class="bi bi-cash-coin me-2"></i>
            Change to return: <strong>${{ number_format(abs($balance), 2) }}</strong>
        </div>
    @elseif($balance > 0)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle me-2"></i>
            Amount due: <strong>${{ number_format($balance, 2) }}</strong>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-check-circle me-2"></i>
            Bill settled. No balance due.
        </div>
    @endif

    {{-- Add Payment Modal --}}
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('folios.items.store', $folio->folio_code) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addPaymentModalLabel">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add New Payment
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="type" value="payment">
                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label">Amount (USD) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="paymentAmount" name="amount" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="paymentReference" class="form-label">Payment Method/Reference <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="paymentReference" name="reference" placeholder="e.g., Cash, Credit Card, Check #123" required>
                        </div>
                        <div class="mb-3">
                            <label for="paymentDate" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="paymentDate" name="posted_at" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Add Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('fullscreen-loader');
    loader.classList.add('hidden');

    document.querySelectorAll('a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && !href.startsWith('http') && !href.startsWith('#') && !link.hasAttribute('target')) {
            link.addEventListener('click', e => {
                e.preventDefault();
                loader.classList.remove('hidden');
                setTimeout(() => window.location.href = href, 200);
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Confirm bill button
    $('#confirmBillBtn').click(function() {
        if (confirm('Are you sure you want to confirm this bill? This action cannot be undone.')) {
            // Add your confirm bill logic here
            alert('Bill confirmed successfully!');
        }
    });

    // Auto-calculate change when payment amount is entered
    $('#paymentAmount').on('input', function() {
        const paymentAmount = parseFloat($(this).val()) || 0;
        const balanceDue = {{ $balance }};
        
        if (paymentAmount > balanceDue && balanceDue > 0) {
            const change = paymentAmount - balanceDue;
            $(this).after('<small class="text-success">Change: $' + change.toFixed(2) + '</small>');
        } else {
            $(this).siblings('small').remove();
        }
    });
});
</script>
@endpush
