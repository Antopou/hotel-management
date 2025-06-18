@extends('layouts.main')

@section('title', 'Folios - Hotel Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Folios</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Guest Folios</h1>
        <p class="page-subtitle">Manage guest billing and financial records</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-download me-2"></i>
            Export
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFolioModal">
            <i class="bi bi-plus-circle me-2"></i>
            New Folio
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0e7ff;">Total Folios</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">{{ $folios->total() }}</h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-receipt fs-2" style="color: #6366f1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0ffe0;">Total Revenue</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-currency-dollar fs-2" style="color: #10b981;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #fde4ff;">Pending Payment</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($pendingPayments ?? 0, 2) }}</h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-clock fs-2" style="color: #f43f5e;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2" style="color: #e0f7fa;">Avg. Folio Value</h6>
                        <h2 class="card-title mb-0 fw-bold" style="color: #fff;">${{ number_format($avgFolioValue ?? 0, 2) }}</h2>
                    </div>
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fff;">
                        <i class="bi bi-graph-up fs-2" style="color: #0ea5e9;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('folios.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="guest" class="form-label">Guest Name</label>
                <input type="text" name="guest" id="guest" value="{{ request('guest') }}" 
                       class="form-control" placeholder="Search by guest name">
            </div>
            <div class="col-md-3">
                <label for="folio_code" class="form-label">Folio Code</label>
                <input type="text" name="folio_code" id="folio_code" value="{{ request('folio_code') }}" 
                       class="form-control" placeholder="Enter folio code">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('folios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Folios Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Folio Code</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Total Amount</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($folios as $folio)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-receipt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $folio->folio_code }}</h6>
                                    <small class="text-muted">{{ $folio->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-0">{{ $folio->checkin->guest->name ?? 'N/A' }}</h6>
                                <small class="text-muted">{{ $folio->checkin->guest->email ?? '' }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $folio->checkin->room->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-sm">
                                {{ $folio->checkin->checkin_date ? \Carbon\Carbon::parse($folio->checkin->checkin_date)->format('M d, Y') : 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $folio->checkin->checkin_date ? \Carbon\Carbon::parse($folio->checkin->checkin_date)->format('H:i') : '' }}</small>
                            </div>
                        </td>
                        <td>
                            <strong class="text-success">${{ number_format($folio->total_amount ?? 0, 2) }}</strong>
                        </td>
                        <td>
                            @php
                                $balance = ($folio->total_amount ?? 0) - ($folio->total_paid ?? 0);
                            @endphp
                            <strong class="{{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($balance, 2) }}
                            </strong>
                        </td>
                        <td>
                            @php
                                $statusClass = match($folio->status ?? 'open') {
                                    'closed' => 'bg-secondary',
                                    'paid' => 'bg-success',
                                    default => 'bg-warning'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($folio->status ?? 'open') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('folios.show', $folio->folio_code) }}" class="btn btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('folios.print', $folio->folio_code) }}" class="btn btn-outline-primary" title="Print" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <button class="btn btn-outline-success" onclick="addPayment('{{ $folio->folio_code }}')" title="Add Payment">
                                    <i class="bi bi-credit-card"></i>
                                </button>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editFolioModal{{ $folio->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @include('folios._modals', ['folio' => $folio])
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-3 text-muted"></i>
                                <h5>No folios found</h5>
                                <p>Start by creating your first folio</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFolioModal">
                                    <i class="bi bi-plus-circle me-2"></i>Create Folio
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
@if($folios->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $folios->links('pagination::bootstrap-5') }}
</div>
@endif

@include('folios._modal_create')
@include('folios._modal_export')
@endsection

@push('scripts')
<script>
function addPayment(folioCode) {
    // You can implement a payment modal or redirect to payment page
    const amount = prompt('Enter payment amount:');
    if (amount && !isNaN(amount) && parseFloat(amount) > 0) {
        fetch(`/folios/${folioCode}/payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                amount: parseFloat(amount),
                payment_method: 'cash',
                notes: 'Payment added via quick action'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to add payment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
@endpush
