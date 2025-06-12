
<!-- Quick Check-in Modal -->
<div class="modal fade" id="quickCheckinModal" tabindex="-1" aria-labelledby="quickCheckinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="quickCheckinModalLabel">
                    <i class="bi bi-person-plus"></i> Walk-in Check-in
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('checkins.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="room_code" class="form-label">Room</label>
                        <select name="room_code" id="room_code" class="form-select" required>
                            <option value="">Select Room</option>
                            @foreach($rooms->where('status', 'available') as $availableRoom)
                                <option value="{{ $availableRoom->room_code }}">{{ $availableRoom->name }} ({{ $availableRoom->roomType->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="guest_code" class="form-label">Guest</label>
                        <select name="guest_code" id="guest_code" class="form-select" required>
                            <option value="">Select Guest</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->guest_code }}">{{ $guest->name }} ({{ $guest->phone }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <a href="{{ route('guests.create') }}" target="_blank">Add new guest</a>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="checkin_date" class="form-label">Check-in Date</label>
                            <input type="datetime-local" name="checkin_date" id="checkin_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="number_of_guest" class="form-label">Number of Guests</label>
                            <input type="number" name="number_of_guest" id="number_of_guest" class="form-control" min="1" value="1" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-check"></i> Check-in Guest
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
