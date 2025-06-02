@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0">New Check-in</h4>
                    <a href="{{ route('checkins.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkins.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="guest_code" class="form-label">Guest</label>
                            <select name="guest_code" class="form-select" required>
                                <option value="">Select Guest</option>
                                @foreach($guests as $guest)
                                    <option value="{{ $guest->guest_code }}">{{ $guest->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="room_code" class="form-label">Room</label>
                            <select name="room_code" class="form-select" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->room_code }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="reservation_ref" class="form-label">Reservation (optional)</label>
                            <select name="reservation_ref" class="form-select">
                                <option value="">None</option>
                                @foreach($reservations as $res)
                                    <option value="{{ $res->reservation_code }}">
                                        {{ $res->reservation_code }} - {{ $res->guest->name ?? 'Unknown' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="checkin_date" class="form-label">Check-in Date</label>
                            <input type="date" name="checkin_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="checkout_date" class="form-label">Check-out Date</label>
                            <input type="date" name="checkout_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="number_of_guest" class="form-label">Number of Guests</label>
                            <input type="number" name="number_of_guest" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <input type="text" name="payment_method" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="rate" class="form-label">Rate</label>
                            <input type="number" name="rate" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="total_payment" class="form-label">Total Payment</label>
                            <input type="number" name="total_payment" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('checkins.index') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
