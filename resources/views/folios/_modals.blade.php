{{-- folios/_modals.blade.php --}}
{{-- $folio is expected --}}

{{-- Edit Folio Modal --}}
<div class="modal fade" id="editFolioModal{{ $folio->id }}" tabindex="-1" aria-labelledby="editFolioLabel{{ $folio->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('folios.update', $folio->folio_code) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editFolioLabel{{ $folio->id }}">Edit Folio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Folio Code</label>
                            <input type="text" class="form-control" value="{{ $folio->folio_code }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="open" {{ $folio->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ $folio->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="paid" {{ $folio->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Guest</label>
                            <input type="text" class="form-control" value="{{ $folio->checkin->guest->name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Room</label>
                            <input type="text" class="form-control" value="{{ $folio->checkin->room->name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Amount</label>
                            <input type="number" name="total_amount" class="form-control" value="{{ $folio->total_amount }}" step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Paid</label>
                            <input type="number" name="total_paid" class="form-control" value="{{ $folio->total_paid }}" step="0.01" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $folio->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Folio</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Payment Modal --}}
<div class="modal fade" id="addPaymentModal{{ $folio->id }}" tabindex="-1" aria-labelledby="addPaymentLabel{{ $folio->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('folios.payments.store', $folio->folio_code) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addPaymentLabel{{ $folio->id }}">Add Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                        <div class="form-text">
                            Outstanding Balance: ${{ number_format(($folio->total_amount ?? 0) - ($folio->paid_amount ?? 0), 2) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" class="form-control" placeholder="Transaction/Check number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Payment notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
