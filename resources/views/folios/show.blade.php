@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            <i class="bi bi-receipt me-2"></i>
            Folio #{{ $folio->folio_code ?? $folio->id }}
        </h3>
        <div>
            <a href="{{ route('folios.index') }}" class="btn btn-light me-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('folios.print', $folio->folio_code ?? $folio->id) }}" class="btn btn-dark" target="_blank">
                <i class="bi bi-printer"></i> Print
            </a>
        </div>
    </div>

    {{-- Folio Summary --}}
    <div class="card mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Guest Name</dt>
                <dd class="col-md-9">{{ $folio->guest->name ?? '-' }}</dd>

                <dt class="col-md-3">Room</dt>
                <dd class="col-md-9">{{ $folio->room->name ?? '-' }}</dd>

                <dt class="col-md-3">Folio Date</dt>
                <dd class="col-md-9">{{ $folio->created_at ? \Carbon\Carbon::parse($folio->created_at)->format('Y-m-d H:i') : '-' }}</dd>

                <dt class="col-md-3">Linked Reservation</dt>
                <dd class="col-md-9">
                    @if($folio->checkin && $folio->checkin->reservation)
                        <a href="{{ route('reservations.show', $folio->checkin->reservation->id) }}">
                            #{{ $folio->checkin->reservation->reservation_code ?? $folio->checkin->reservation->id }}
                        </a>
                    @else
                        -
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- Charges Table --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Charges</span>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items->where('type', 'charge') as $charge)
                        <tr>
                            <td>{{ $charge->posted_at ? \Carbon\Carbon::parse($charge->posted_at)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $charge->description }}</td>
                            <td class="text-end">{{ number_format($charge->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No charges.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Payments</span>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items->where('type', 'payment') as $payment)
                        <tr>
                            <td>{{ $payment->posted_at ? \Carbon\Carbon::parse($payment->posted_at)->format('Y-m-d') : '-' }}</td>
                            <td>{{ $payment->reference ?? '-' }}</td>
                            <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No payments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totals --}}
    @php
        $totalCharges = $items->where('type', 'charge')->sum('amount');
        $totalPayments = $items->where('type', 'payment')->sum('amount');
        $balance = $totalCharges - $totalPayments;
    @endphp
    <div class="row">
        <div class="col-lg-6 offset-lg-6">
            <table class="table table-borderless">
                <tr>
                    <th class="text-end">Total Charges:</th>
                    <td class="text-end">{{ number_format($totalCharges, 2) }}</td>
                </tr>
                <tr>
                    <th class="text-end">Total Payments:</th>
                    <td class="text-end">{{ number_format($totalPayments, 2) }}</td>
                </tr>
                <tr>
                    <th class="text-end">Balance:</th>
                    <td class="text-end fw-bold">{{ number_format($balance, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Add JS if you want modal actions --}}
@endsection
