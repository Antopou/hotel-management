{{-- resources/views/folios/_modal_add_item.blade.php --}}
<div class="modal fade" id="addItemModal{{ $folio->id }}" tabindex="-1" aria-labelledby="addItemLabel{{ $folio->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('folios.items.store', $folio->folio_code) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addItemLabel{{ $folio->id }}">
                        <i class="bi bi-plus-circle"></i> Add Item to Bill
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="itemDescription{{ $folio->id }}" class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" name="description" id="itemDescription{{ $folio->id }}" class="form-control" required placeholder="e.g., Room Service, Laundry, etc.">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="itemQuantity{{ $folio->id }}" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="itemQuantity{{ $folio->id }}" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="itemPrice{{ $folio->id }}" class="form-label">Unit Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="unit_price" id="itemPrice{{ $folio->id }}" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="itemCategory{{ $folio->id }}" class="form-label">Category</label>
                        <select name="category" id="itemCategory{{ $folio->id }}" class="form-select">
                            <option value="">-- Select Category --</option>
                            <option value="Room">Room Charges</option>
                            <option value="Food & Beverage">Food & Beverage</option>
                            <option value="Laundry">Laundry</option>
                            <option value="Telephone">Telephone</option>
                            <option value="Minibar">Minibar</option>
                            <option value="Spa">Spa Services</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="itemNotes{{ $folio->id }}" class="form-label">Notes</label>
                        <textarea name="notes" id="itemNotes{{ $folio->id }}" class="form-control" rows="2" placeholder="Optional notes about this item"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
