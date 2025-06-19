{{-- Create Folio Modal --}}
<div class="modal fade" id="createFolioModal" tabindex="-1" aria-labelledby="createFolioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('folios.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createFolioLabel">Create New Folio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Check-in <span class="text-danger">*</span></label>
                            <select name="checkin_id" class="form-select" required>
                                <option value="">Select Check-in</option>
                                @foreach($checkins ?? [] as $checkin)
                                    <option value="{{ $checkin->id }}">
                                        {{ $checkin->guest->name ?? 'N/A' }} - {{ $checkin->room->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="open" selected>Open</option>
                                <option value="closed">Closed</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Amount</label>
                            <input type="number" name="total_amount" class="form-control" step="0.01" min="0" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Paid</label>
                            <input type="number" name="total_paid" class="form-control" step="0.01" min="0" value="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Folio notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Folio</button>
                </div>
            </form>
        </div>
    </div>
</div>
