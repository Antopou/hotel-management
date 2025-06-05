{{-- View Check-in Modal --}}
<div class="modal fade" id="viewCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="viewCheckinLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewCheckinLabel{{ $checkin->id }}">Check-in Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Guest</dt>
                    <dd class="col-sm-8">{{ $checkin->guest->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Room</dt>
                    <dd class="col-sm-8">{{ $checkin->room->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Check-in</dt>
                    <dd class="col-sm-8">{{ $checkin->checkin_date }}</dd>
                    <dt class="col-sm-4">Check-out</dt>
                    <dd class="col-sm-8">{{ $checkin->checkout_date }}</dd>
                    <dt class="col-sm-4">Guests</dt>
                    <dd class="col-sm-8">{{ $checkin->number_of_guest }}</dd>
                    <dt class="col-sm-4">Rate</dt>
                    <dd class="col-sm-8">{{ $checkin->rate ?? '0.00' }}</dd>
                    <dt class="col-sm-4">Total Payment</dt>
                    <dd class="col-sm-8">{{ $checkin->total_payment ?? '0.00' }}</dd>
                    <dt class="col-sm-4">Payment Method</dt>
                    <dd class="col-sm-8">{{ $checkin->payment_method ?? '-' }}</dd>
                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">{{ $checkin->is_checkout ? 'Checked Out' : 'In Stay' }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Check-in Modal --}}
<div class="modal fade" id="editCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="editCheckinLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('checkins.update', $checkin->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editCheckinLabel{{ $checkin->id }}">Edit Check-in</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Guest</label>
                        <select name="guest_code" class="form-select" required>
                            @foreach ($guests as $guest)
                                <option value="{{ $guest->guest_code }}" {{ $checkin->guest_code === $guest->guest_code ? 'selected' : '' }}>
                                    {{ $guest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Room</label>
                        <select name="room_code" class="form-select" required>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->room_code }}" {{ $checkin->room_code === $room->room_code ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-in Date</label>
                        <input type="datetime-local" name="checkin_date" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-out Date</label>
                        <input type="datetime-local" name="checkout_date" class="form-control" value="{{ \Carbon\Carbon::parse($checkin->checkout_date)->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guests</label>
                        <input type="number" name="number_of_guest" class="form-control" value="{{ $checkin->number_of_guest }}">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_checkout" value="1" {{ $checkin->is_checkout ? 'checked' : '' }}>
                        <label class="form-check-label">Mark as Checked Out</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Check-in Modal --}}
<div class="modal fade" id="deleteCheckinModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="deleteCheckinLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('checkins.destroy', $checkin->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCheckinLabel{{ $checkin->id }}">Confirm Delete</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the check-in for <strong>{{ $checkin->guest->name ?? 'N/A' }}</strong>?</p>
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
