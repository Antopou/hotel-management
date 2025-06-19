{{-- resources/views/guests/_modal_show.blade.php --}}
<div class="modal fade" id="showGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="showGuestLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showGuestLabel{{ $guest->id }}">
                    <i class="bi bi-person-badge"></i> Guest Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-muted">PERSONAL INFORMATION</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-5">Guest Code:</dt>
                                    <dd class="col-sm-7">{{ $guest->guest_code }}</dd>
                                    <dt class="col-sm-5">Name:</dt>
                                    <dd class="col-sm-7">{{ $guest->name }}</dd>
                                    <dt class="col-sm-5">Email:</dt>
                                    <dd class="col-sm-7">{{ $guest->email ?: '-' }}</dd>
                                    <dt class="col-sm-5">Phone:</dt>
                                    <dd class="col-sm-7">{{ $guest->tel ?: '-' }}</dd>
                                    <dt class="col-sm-5">Gender:</dt>
                                    <dd class="col-sm-7">{{ $guest->gender ?: '-' }}</dd>
                                    <dt class="col-sm-5">Address:</dt>
                                    <dd class="col-sm-7">{{ $guest->address ?: '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-muted">STAY HISTORY</h6>
                            </div>
                            <div class="card-body">
                                @if($guest->checkins && $guest->checkins->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Room</th>
                                                    <th>Check-in</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($guest->checkins->take(5) as $checkin)
                                                <tr>
                                                    <td>{{ $checkin->room->name ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $checkin->is_checkout ? 'secondary' : 'success' }} badge-sm">
                                                            {{ $checkin->is_checkout ? 'Out' : 'In' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($guest->checkins->count() > 5)
                                        <small class="text-muted">Showing latest 5 stays</small>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">No stay history available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-muted">ACCOUNT INFORMATION</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-3">Created:</dt>
                                    <dd class="col-sm-3">{{ $guest->created_at->format('M d, Y H:i') }}</dd>
                                    <dt class="col-sm-3">Last Updated:</dt>
                                    <dd class="col-sm-3">{{ $guest->updated_at->format('M d, Y H:i') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
