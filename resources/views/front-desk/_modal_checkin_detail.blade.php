@if(isset($checkin))
<div class="modal fade" id="checkinDetailModal{{ $checkin->id }}" tabindex="-1" aria-labelledby="checkinDetailModalLabel{{ $checkin->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fs-4" id="checkinDetailModalLabel{{ $checkin->id }}">
                    <i class="bi bi-info-circle me-2"></i>Check-in Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="font-size: 1rem;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3 fs-5">Guest Information</h6>
                        <dl class="row">
                            <dt class="col-sm-4 fs-6">Name:</dt>
                            <dd class="col-sm-8 fs-6">{{ $checkin->guest->name ?? 'N/A' }}</dd>
                            <dt class="col-sm-4 fs-6">Email:</dt>
                            <dd class="col-sm-8 fs-6">{{ $checkin->guest->email ?? 'N/A' }}</dd>
                            <dt class="col-sm-4 fs-6">Phone:</dt>
                            <dd class="col-sm-8 fs-6">{{ $checkin->guest->phone ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success mb-3 fs-5">Room Information</h6>
                        <dl class="row">
                            <dt class="col-sm-4 fs-6">Room:</dt>
                            <dd class="col-sm-8 fs-6">{{ $checkin->room->name ?? 'N/A' }}</dd>
                            <dt class="col-sm-4 fs-6">Type:</dt>
                            <dd class="col-sm-8 fs-6">{{ $checkin->room->roomType->name ?? 'N/A' }}</dd>
                            <dt class="col-sm-4 fs-6">Rate:</dt>
                            <dd class="col-sm-8 fs-6">${{ number_format($checkin->room->roomType->price_per_night ?? 0, 2) }}/night</dd>
                        </dl>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold text-warning mb-3 fs-5">Stay Information</h6>
                        <dl class="row">
                            <dt class="col-sm-3 fs-6">Check-in:</dt>
                            <dd class="col-sm-3 fs-6">{{ $checkin->checkin_date ? \Carbon\Carbon::parse($checkin->checkin_date)->format('M d, Y H:i') : 'N/A' }}</dd>
                            <dt class="col-sm-3 fs-6">Check-out:</dt>
                            <dd class="col-sm-3 fs-6">{{ $checkin->checkout_date ? \Carbon\Carbon::parse($checkin->checkout_date)->format('M d, Y H:i') : 'N/A' }}</dd>
                            <dt class="col-sm-3 fs-6">Duration:</dt>
                            <dd class="col-sm-9 fs-6">
                                @if($checkin->checkin_date && $checkin->checkout_date)
                                    {{ \Carbon\Carbon::parse($checkin->checkin_date)->diffInDays(\Carbon\Carbon::parse($checkin->checkout_date)) }} night(s)
                                @else
                                    N/A
                                @endif
                            </dd>
                            <dt class="col-sm-3 fs-6">Status:</dt>
                            <dd class="col-sm-9 fs-6">
                                @if($checkin->is_checkout)
                                    <span class="badge bg-secondary fs-6">Checked Out</span>
                                @else
                                    <span class="badge bg-success fs-6">Active</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                    @if($checkin->notes)
                    <div class="col-12">
                        <h6 class="fw-bold text-secondary mb-3 fs-5">Notes</h6>
                        <div class="alert alert-light" style="font-size: 1rem;">
                            {{ $checkin->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                @if(!$checkin->is_checkout)
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCheckinModal{{ $checkin->id }}" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </button>
                    <form action="{{ route('checkins.checkout', $checkin->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to check out this guest?')" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                            <i class="bi bi-box-arrow-right me-2"></i>Check Out
                        </button>
                    </form>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif
