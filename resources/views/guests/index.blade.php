@extends('layouts.main')

@section('content')
@include('partials.loader')
<div class="px-3 py-4">
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Guest Management</h3>
        <a href="{{ route('guests.create') }}" class="btn btn-success">Add New</a>
    </div>

    {{-- Toast Notifications --}}
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div class="toast text-bg-success border-0" role="alert" id="successToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="toast text-bg-danger border-0" role="alert" id="errorToast" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" action="{{ route('guests.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Search by Name">
        </div>
        <div class="col-md-3">
            <input type="text" name="tel" value="{{ request('tel') }}" class="form-control" placeholder="Search by Phone">
        </div>
        <div class="col-md-3">
            <select name="gender" class="form-select">
                <option value="">All Genders</option>
                <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guests as $guest)
                <tr>
                    <td>{{ $guest->id }}</td>
                    <td>{{ $guest->name }}</td>
                    <td>{{ $guest->tel ?? '-' }}</td>
                    <td>{{ $guest->email ?? '-' }}</td>
                    <td>{{ $guest->gender ?? '-' }}</td>
                    <td>
                        <!-- View -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewGuestModal{{ $guest->id }}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <!-- Edit -->
                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <!-- Delete -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $guest->id }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>

                <!-- View Modal -->
                <div class="modal fade" id="viewGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="viewGuestLabel{{ $guest->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewGuestLabel{{ $guest->id }}">Guest Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Name</dt>
                                    <dd class="col-sm-8">{{ $guest->name }}</dd>
                                    <dt class="col-sm-4">Phone</dt>
                                    <dd class="col-sm-8">{{ $guest->tel ?? '-' }}</dd>
                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ $guest->email ?? '-' }}</dd>
                                    <dt class="col-sm-4">Gender</dt>
                                    <dd class="col-sm-8">{{ $guest->gender ?? '-' }}</dd>
                                </dl>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="editGuestLabel{{ $guest->id }}" aria-hidden="true">
                    <div class="modal-dialog custom-modal">
                        <div class="modal-content">
                            <form action="{{ route('guests.update', $guest->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGuestLabel{{ $guest->id }}">Edit Guest</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="name{{ $guest->id }}">Name</label>
                                        <input type="text" name="name" id="name{{ $guest->id }}" class="form-control" value="{{ $guest->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="tel{{ $guest->id }}">Phone</label>
                                        <input type="text" name="tel" id="tel{{ $guest->id }}" class="form-control" value="{{ $guest->tel }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="email{{ $guest->id }}">Email</label>
                                        <input type="email" name="email" id="email{{ $guest->id }}" class="form-control" value="{{ $guest->email }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="gender{{ $guest->id }}">Gender</label>
                                        <select name="gender" id="gender{{ $guest->id }}" class="form-select">
                                            <option value="">-- Select --</option>
                                            <option value="Male" {{ $guest->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $guest->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
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

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="deleteGuestLabel{{ $guest->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form action="{{ route('guests.destroy', $guest->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteGuestLabel{{ $guest->id }}">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <strong>{{ $guest->name }}</strong>?</p>
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
                <tr>
                    <td colspan="6">
                        <div class="alert alert-info mb-0">Guest not exists.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $guests->links('pagination::bootstrap-5') }}
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
