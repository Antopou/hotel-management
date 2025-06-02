@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Check-in Details</h4>
                    <a href="{{ route('checkins.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <div class="card-body">
                    <p><strong>Guest:</strong> {{ $checkin->guest->name ?? 'N/A' }}</p>
                    <p><strong>Room:</strong> {{ $checkin->room->name ?? 'N/A' }}</p>
                    <p><strong>Check-in Date:</strong> {{ $checkin->checkin_date }}</p>
                    <p><strong>Check-out Date:</strong> {{ $checkin->checkout_date }}</p>
                    <p><strong>Guests:</strong> {{ $checkin->number_of_guest }}</p>
                    <p><strong>Payment Method:</strong> {{ $checkin->payment_method ?? '-' }}</p>
                    <p><strong>Rate:</strong> ${{ $checkin->rate ?? 0 }}</p>
                    <p><strong>Total:</strong> ${{ $checkin->total_payment ?? 0 }}</p>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge {{ $checkin->is_checkout ? 'bg-secondary' : 'bg-success' }}">
                            {{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
