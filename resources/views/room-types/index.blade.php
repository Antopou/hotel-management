@extends('layouts.main')

@section('content')
@include('partials.loader')

<div class="container-fluid py-4"> {{-- Changed from px-3 py-4 to container-fluid py-4 for consistency with main layout --}}
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Room Type Management</h3>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createRoomTypeModal">
            <i class="bi bi-plus-circle me-2"></i> Add New Room Type
        </button>
    </div>

    {{-- Search Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('room-types.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4 flex-grow-1">
                    <label for="searchName" class="form-label">Room Type Name</label>
                    <input type="text" name="name" id="searchName" class="form-control" placeholder="Search by Room Type Name" value="{{ request('name') }}">
                </div>
                <div class="col-12 col-md-2 flex-grow-1">
                    <label for="minPrice" class="form-label">Min Price</label>
                    <input type="number" name="min_price" id="minPrice" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
                </div>
                <div class="col-12 col-md-2 flex-grow-1">
                    <label for="maxOccupancy" class="form-label">Max Occupancy</label>
                    <input type="number" name="max_occupancy" id="maxOccupancy" class="form-control" placeholder="Max Guests" value="{{ request('max_occupancy') }}">
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i> Search
                    </button>
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Card-style Room Type List (Grid View) --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
        @forelse ($roomTypes as $type)
            @php
                $imageUrl = $type->image ? asset('storage/' . $type->image) : asset('images/room_types/default.jpg');
            @endphp
            <div class="col">
                <div class="card h-100 shadow-sm border-0 card-hover">
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $type->name }}" style="height: 180px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary mb-1">{{ $type->name }}</h5>
                        <p class="card-text text-muted small mb-1"><strong>Code:</strong> {{ $type->room_type_code }}</p>
                        <p class="card-text small mb-1">
                            <strong>Price/Night:</strong> <span class="fw-bold text-success">${{ number_format($type->price_per_night, 2) }}</span>
                        </p>
                        <p class="card-text small">
                            <strong>Max Occupancy:</strong> <span class="fw-bold">{{ $type->max_occupancy }}</span>
                        </p>
                        {{-- Optional: Short description snippet --}}
                        {{-- <p class="card-text small">{{ Str::limit($type->description, 70) ?: 'No description.' }}</p> --}}
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3 d-flex justify-content-center gap-2">
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomTypeModal{{ $type->id }}">
                            <i class="bi bi-eye-fill"></i> View
                        </button>
                        <button class="btn btn-outline-secondary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editRoomTypeModal{{ $type->id }}"
                                data-id="{{ $type->id }}"
                                data-name="{{ $type->name }}"
                                data-description="{{ $type->description }}"
                                data-price="{{ $type->price_per_night }}"
                                data-occupancy="{{ $type->max_occupancy }}"
                                data-image="{{ $type->image ? asset('storage/' . $type->image) : asset('images/room_types/default.jpg') }}">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteRoomTypeModal{{ $type->id }}"
                                data-id="{{ $type->id }}"
                                data-name="{{ $type->name }}">
                            <i class="bi bi-trash-fill"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

            {{-- View Modal (remains unchanged, as it already contains all details) --}}
            <div class="modal fade" id="viewRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="viewRoomTypeLabel{{ $type->id }}" aria-hidden="true">
                <div class="modal-dialog custom-modal">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="viewRoomTypeLabel{{ $type->id }}">Room Type Details - {{ $type->name }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <img src="{{ $imageUrl }}" alt="{{ $type->name }}" class="img-fluid rounded shadow-sm">
                                </div>
                                <div class="col-md-7">
                                    <h4 class="mb-3">{{ $type->name }} <small class="text-muted fs-6">({{ $type->room_type_code }})</small></h4>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 text-muted">Description:</dt>
                                        <dd class="col-sm-8">{{ $type->description ?: 'N/A' }}</dd>

                                        <dt class="col-sm-4 text-muted">Price/Night:</dt>
                                        <dd class="col-sm-8 fw-bold text-success">${{ number_format($type->price_per_night, 2) }}</dd>

                                        <dt class="col-sm-4 text-muted">Max Occupancy:</dt>
                                        <dd class="col-sm-8 fw-bold">{{ $type->max_occupancy }}</dd>

                                        <dt class="col-sm-4 text-muted">Created By:</dt>
                                        <dd class="col-sm-8">{{ $type->created_by ?? 'N/A' }}</dd> {{-- Assuming created_by field exists or can be derived --}}

                                        <dt class="col-sm-4 text-muted">Created At:</dt>
                                        <dd class="col-sm-8">{{ $type->created_at->format('M d, Y H:i A') }}</dd>

                                        @if ($type->modified_by)
                                            <dt class="col-sm-4 text-muted">Modified By:</dt>
                                            <dd class="col-sm-8">{{ $type->modified_by ?? 'N/A' }}</dd>
                                            <dt class="col-sm-4 text-muted">Last Modified:</dt>
                                            <dd class="col-sm-8">{{ $type->updated_at->format('M d, Y H:i A') }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Modal (Updated to use dynamic data via JS for a single modal) --}}
            <div class="modal fade" id="editRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="editRoomTypeLabel{{ $type->id }}" aria-hidden="true">
                <div class="modal-dialog custom-modal">
                    <div class="modal-content">
                        <form id="editRoomTypeForm{{ $type->id }}" action="{{ route('room-types.update', $type->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editRoomTypeLabel{{ $type->id }}">Edit Room Type</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name_{{ $type->id }}" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name_{{ $type->id }}" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $type->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_description_{{ $type->id }}" class="form-label">Description</label>
                                    <textarea name="description" id="edit_description_{{ $type->id }}" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $type->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_price_per_night_{{ $type->id }}" class="form-label">Price per Night <span class="text-danger">*</span></label>
                                    <input type="number" name="price_per_night" id="edit_price_per_night_{{ $type->id }}" step="0.01" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night', $type->price_per_night) }}" required>
                                    @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_max_occupancy_{{ $type->id }}" class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                                    <input type="number" name="max_occupancy" id="edit_max_occupancy_{{ $type->id }}" class="form-control @error('max_occupancy') is-invalid @enderror" value="{{ old('max_occupancy', $type->max_occupancy) }}" required>
                                    @error('max_occupancy') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_image_{{ $type->id }}" class="form-label">Image</label>
                                    <input type="file" name="image" id="edit_image_{{ $type->id }}" class="form-control @error('image') is-invalid @enderror">
                                    @if($type->image)
                                        <small class="text-muted mt-1 d-block">Current: <a href="{{ asset('storage/' . $type->image) }}" target="_blank">{{ basename($type->image) }}</a></small>
                                    @endif
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-arrow-repeat me-2"></i> Update Room Type
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Delete Modal --}}
            <div class="modal fade" id="deleteRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="deleteRoomTypeLabel{{ $type->id }}" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <form action="{{ route('room-types.destroy', $type->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteRoomTypeLabel{{ $type->id }}">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete the room type <strong>{{ $type->name }}</strong>?</p>
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
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i> No room types available. Try adjusting your search or add a new room type.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($roomTypes->hasPages())
        <div class="d-flex justify-content-center">
            {{ $roomTypes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Create Room Type Modal (kept outside the main div as per your original, but added styling) --}}
<div class="modal fade" id="createRoomTypeModal" tabindex="-1" aria-labelledby="createRoomTypeLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createRoomTypeLabel">Add New Room Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create_name" class="form-label">Room Type Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_description" class="form-label">Description</label>
                        <textarea name="description" id="create_description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_price_per_night" class="form-label">Price per Night <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_night" id="create_price_per_night" step="0.01" class="form-control @error('price_per_night') is-invalid @enderror" value="{{ old('price_per_night') }}" required>
                        @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_max_occupancy" class="form-label">Max Occupancy <span class="text-danger">*</span></label>
                        <input type="number" name="max_occupancy" id="create_max_occupancy" class="form-control @error('max_occupancy') is-invalid @enderror" value="{{ old('max_occupancy') }}" required>
                        @error('max_occupancy') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="create_image" class="form-label">Image</label>
                        <input type="file" name="image" id="create_image" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-2"></i> Save Room Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successToastEl = document.getElementById('successToast');
        const errorToastEl = document.getElementById('errorToast');

        if (successToastEl) {
            new bootstrap.Toast(successToastEl, { autohide: true, delay: 5000 }).show();
        }

        if (errorToastEl) {
            new bootstrap.Toast(errorToastEl, { autohide: true, delay: 5000 }).show();
        }
    });
</script>

@if ($errors->any())
    @php
        // Detect which modal to show:
        // - If creating: old('name') and no edit_modal_id in session
        // - If editing: session('edit_modal_id') is present
        $editModalId = session('edit_modal_id');
    @endphp

    @if (old('name') && !$editModalId)
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var createModal = new bootstrap.Modal(document.getElementById('createRoomTypeModal'));
                createModal.show();
            });
        </script>
    @elseif ($editModalId)
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById('editRoomTypeModal{{ $editModalId }}'));
                editModal.show();
            });
        </script>
    @endif
@endif
@endsection