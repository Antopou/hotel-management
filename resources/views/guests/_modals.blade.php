{{-- guests/_modals.blade.php --}}
{{-- $guest is expected --}}

{{-- View Guest Modal --}}
<div class="modal fade" id="viewGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="viewGuestLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewGuestLabel{{ $guest->id }}">Guest Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Guest Code</dt>
                            <dd class="col-sm-7">{{ $guest->guest_code }}</dd>
                            <dt class="col-sm-5">Full Name</dt>
                            <dd class="col-sm-7">{{ $guest->name }}</dd>
                            <dt class="col-sm-5">Email</dt>
                            <dd class="col-sm-7">{{ $guest->email ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">Phone</dt>
                            <dd class="col-sm-7">{{ $guest->tel ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">Gender</dt>
                            <dd class="col-sm-7">{{ $guest->gender ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">Address</dt>
                            <dd class="col-sm-7">{{ $guest->address ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Date of Birth</dt>
                            <dd class="col-sm-7">{{ $guest->date_of_birth ? \Carbon\Carbon::parse($guest->date_of_birth)->format('M d, Y') : 'N/A' }}</dd>
                            <dt class="col-sm-5">Nationality</dt>
                            <dd class="col-sm-7">{{ $guest->nationality ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">ID Number</dt>
                            <dd class="col-sm-7">{{ $guest->id_number ?? 'N/A' }}</dd>
                            <dt class="col-sm-5">Member Since</dt>
                            <dd class="col-sm-7">{{ $guest->created_at->format('M d, Y') }}</dd>
                            <dt class="col-sm-5">Total Stays</dt>
                            <dd class="col-sm-7">{{ $guest->checkins_count ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
                
                @if($guest->notes)
                <div class="mt-3">
                    <h6>Notes:</h6>
                    <p class="text-muted">{{ $guest->notes }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}">Edit Guest</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Guest Modal --}}
<div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="editGuestLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('guests.update', $guest->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editGuestLabel{{ $guest->id }}">Edit Guest</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $guest->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $guest->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="tel" class="form-control" value="{{ $guest->tel }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ $guest->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $guest->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $guest->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ $guest->date_of_birth ? \Carbon\Carbon::parse($guest->date_of_birth)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality" class="form-control" value="{{ $guest->nationality }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Number</label>
                            <input type="text" name="id_number" class="form-control" value="{{ $guest->id_number }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $guest->address }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $guest->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Guest Modal --}}
<div class="modal fade" id="deleteGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="deleteGuestLabel{{ $guest->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{ route('guests.destroy', $guest->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGuestLabel{{ $guest->id }}">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $guest->name }}</strong>?</p>
                    <p class="text-muted">This action cannot be undone and will also delete all associated reservations and check-ins.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
