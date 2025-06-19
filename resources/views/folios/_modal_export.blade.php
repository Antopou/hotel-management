{{-- Export Folios Modal --}}
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('folios.export') }}" method="GET">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="exportLabel">Export Folios</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select name="format" class="form-select">
                            {{-- <option value="excel">Excel (.xlsx)</option> --}}
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" name="start_date" class="form-control" placeholder="Start Date">
                            </div>
                            <div class="col-6">
                                <input type="date" name="end_date" class="form-control" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Filter</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="include_items" value="1" id="includeItems">
                        <label class="form-check-label" for="includeItems">
                            Include folio items details
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
