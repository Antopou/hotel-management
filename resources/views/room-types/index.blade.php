@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Room Type Management</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRoomTypeModal">Add New</button>
    </div>

    {{-- Toast Notifications --}}
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div class="toast text-bg-success border-0" role="alert" id="successToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="toast text-bg-danger border-0" role="alert" id="errorToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Search Form --}}
    <div class="row mb-4">
        <div class="col-md-6 offset-md-6">
            <form method="GET" action="{{ route('room-types.index') }}" class="d-flex gap-2">
                <input type="text" name="name" class="form-control" placeholder="Search by Room Type Name" value="{{ request('name') }}">
                <button type="submit" class="btn btn-primary" style="width: 104px;">Search</button>
            </form>
        </div>
    </div>

    {{-- Card-style Room Type List --}}
    @forelse ($roomTypes as $type)
        @php
            $imageUrl = $type->image ? asset('storage/' . $type->image) : asset('images/room_types/default.jpg');
        @endphp

        <div class="border rounded mb-3 p-3 shadow-sm bg-white card-hover">
            <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                <img src="{{ $imageUrl }}" alt="{{ $type->name }}" class="rounded" style="width: 120px; height: 90px; object-fit: cover;">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1">{{ $type->name }}</h5>
                            <div class="text-muted small mb-1"><strong>Code:</strong> {{ $type->room_type_code }}</div>
                            {{-- Description, Price/Night, Max Occupancy moved to modal, keeping only essential --}}
                            <div class="text-muted small mb-1"><strong>Price/Night:</strong> ${{ number_format($type->price_per_night, 2) }}</div>
                            <div class="text-muted small"><strong>Max Occupancy:</strong> {{ $type->max_occupancy }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomTypeModal{{ $type->id }}"><i class="bi bi-eye-fill"></i></button>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoomTypeModal{{ $type->id }}"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRoomTypeModal{{ $type->id }}"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- View Modal (remains unchanged, as it already contains all details) --}}
        <div class="modal fade" id="viewRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="viewRoomTypeLabel{{ $type->id }}" aria-hidden="true">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Room Type Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-4">Code</dt>
                            <dd class="col-sm-8">{{ $type->room_type_code }}</dd>

                            <dt class="col-sm-4">Name</dt>
                            <dd class="col-sm-8">{{ $type->name }}</dd>

                            <dt class="col-sm-4">Description</dt>
                            <dd class="col-sm-8">{{ $type->description ?: 'N/A' }}</dd>

                            <dt class="col-sm-4">Price/Night</dt>
                            <dd class="col-sm-8">${{ number_format($type->price_per_night, 2) }}</dd>

                            <dt class="col-sm-4">Max Occupancy</dt>
                            <dd class="col-sm-8">{{ $type->max_occupancy }}</dd>

                            <dt class="col-sm-4">Image</dt>
                            <dd class="col-sm-8"><img src="{{ $imageUrl }}" alt="{{ $type->name }}" class="img-fluid rounded" style="max-width:150px;"></dd>

                            <dt class="col-sm-4">Created At</dt>
                            <dd class="col-sm-8">{{ $type->created_at->format('d M Y') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div class="modal fade" id="editRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="editRoomTypeLabel{{ $type->id }}" aria-hidden="true">
            <div class="modal-dialog custom-modal">
                <div class="modal-content">
                    <form action="{{ route('room-types.update', $type->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Room Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control">{{ $type->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Price per Night</label>
                                <input type="number" name="price_per_night" step="0.01" class="form-control" value="{{ $type->price_per_night }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Max Occupancy</label>
                                <input type="number" name="max_occupancy" class="form-control" value="{{ $type->max_occupancy }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($type->image)
                                    <small class="text-muted">Current: {{ $type->image }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div class="modal fade" id="deleteRoomTypeModal{{ $type->id }}" tabindex="-1" aria-labelledby="deleteRoomTypeLabel{{ $type->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('room-types.destroy', $type->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the room type <strong>{{ $type->name }}</strong>?</p>
                            <p class="text-danger">This action cannot be undone.</p>
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
        <div class="alert alert-info">No room types available.</div>
    @endforelse

    {{-- Pagination --}}
    <div class="pt-3">
        {{ $roomTypes->links('pagination::bootstrap-5') }}
    </div>
</div>
    {{-- Create Room Type Modal (This was already outside the main div in your original code, kept as is) --}}
    <div class="modal fade" id="createRoomTypeModal" tabindex="-1" aria-labelledby="createRoomTypeLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Room Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Room Type Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price_per_night" class="form-label">Price per Night</label>
                            <input type="number" name="price_per_night" step="0.01" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="max_occupancy" class="form-label">Max Occupancy</label>
                            <input type="number" name="max_occupancy" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
            new bootstrap.Toast(successToastEl, { autohide: true, delay: 3000 }).show();
        }

        if (errorToastEl) {
            new bootstrap.Toast(errorToastEl, { autohide: true, delay: 3000 }).show();
        }
    });
</script>
@endsection