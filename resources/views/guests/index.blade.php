@extends('layouts.main')

@section('content')
@include('partials.loader')

<div class="container-fluid py-4"> {{-- Changed from px-3 py-4 to container-fluid py-4 for consistency --}}
    {{-- Page Title and Add Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0 ">Guest Management</h3>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createGuestModal">
            <i class="bi bi-person-plus-fill me-2"></i> Add New Guest
        </button>
    </div>

    {{-- Filter/Search Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('guests.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3"> {{-- Changed from col-md-4 --}}
                    <label for="searchName" class="form-label">Name</label>
                    <input type="text" name="name" id="searchName" value="{{ request('name') }}" class="form-control" placeholder="Search by Name">
                </div>
                <div class="col-md-3">
                    <label for="searchTel" class="form-label">Phone</label>
                    <input type="text" name="tel" id="searchTel" value="{{ request('tel') }}" class="form-control" placeholder="Search by Phone">
                </div>
                <div class="col-md-3">
                    <label for="filterGender" class="form-label">Gender</label>
                    <select name="gender" id="filterGender" class="form-select">
                        <option value="">All Genders</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ request('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2"> {{-- Changed from col-md-auto to col-md-3 --}}
                    <button type="submit" class="btn btn-primary w-100"> {{-- Added w-100 back to make them fill the space --}}
                        <i class="bi bi-search me-2"></i> Filter
                    </button>
                    <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary w-100"> {{-- Added w-100 back --}}
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Guest List Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive"> {{-- Added table-responsive for better mobile experience --}}
                <table class="table table-hover table-striped mb-0"> {{-- Added table-striped and removed table-bordered for a cleaner look --}}
                    <thead class="bg-primary text-white"> {{-- Added bg-primary text-white to table header --}}
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Gender</th>
                            <th scope="col" class="text-center">Actions</th> {{-- Centered actions column --}}
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
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewGuestModal{{ $guest->id }}">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $guest->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="viewGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="viewGuestLabel{{ $guest->id }}" aria-hidden="true">
                                <<div class="modal-dialog custom-modal">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white"> 
                                            <h5 class="modal-title" id="viewGuestLabel{{ $guest->id }}">Guest Details - {{ $guest->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row g-3"> {{-- Added g-3 for spacing --}}
                                                <dt class="col-sm-4 text-muted">Name:</dt>
                                                <dd class="col-sm-8 fw-bold">{{ $guest->name }}</dd>

                                                <dt class="col-sm-4 text-muted">Phone:</dt>
                                                <dd class="col-sm-8">{{ $guest->tel ?? 'N/A' }}</dd>

                                                <dt class="col-sm-4 text-muted">Email:</dt>
                                                <dd class="col-sm-8">{{ $guest->email ?? 'N/A' }}</dd>

                                                <dt class="col-sm-4 text-muted">Gender:</dt>
                                                <dd class="col-sm-8">{{ $guest->gender ?? 'N/A' }}</dd>

                                                <dt class="col-sm-4 text-muted">Created At:</dt>
                                                <dd class="col-sm-8">{{ $guest->created_at->format('M d, Y H:i A') }}</dd>

                                                @if ($guest->updated_at && $guest->updated_at != $guest->created_at) {{-- Check if updated_at is different from created_at --}}
                                                    <dt class="col-sm-4 text-muted">Last Updated:</dt>
                                                    <dd class="col-sm-8">{{ $guest->updated_at->format('M d, Y H:i A') }}</dd>
                                                @endif
                                            </dl>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1" aria-labelledby="editGuestLabel{{ $guest->id }}" aria-hidden="true">
                                <div class="modal-dialog custom-modal">
                                    <div class="modal-content">
                                        <form action="{{ route('guests.update', $guest->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white"> {{-- Styled modal header --}}
                                                <h5 class="modal-title" id="editGuestLabel{{ $guest->id }}">Edit Guest</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit_name_{{ $guest->id }}">Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name_{{ $guest->id }}" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $guest->name) }}" required>
                                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit_tel_{{ $guest->id }}">Phone</label>
                                                    <input type="text" name="tel" id="edit_tel_{{ $guest->id }}" class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel', $guest->tel) }}">
                                                    @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit_email_{{ $guest->id }}">Email</label>
                                                    <input type="email" name="email" id="edit_email_{{ $guest->id }}" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $guest->email) }}">
                                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="edit_gender_{{ $guest->id }}">Gender</label>
                                                    <select name="gender" id="edit_gender_{{ $guest->id }}" class="form-select @error('gender') is-invalid @enderror">
                                                        <option value="">-- Select --</option>
                                                        <option value="Male" {{ old('gender', $guest->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('gender', $guest->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Other" {{ old('gender', $guest->gender) === 'Other' ? 'selected' : '' }}>Other</option> {{-- Added 'Other' option --}}
                                                    </select>
                                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-arrow-repeat me-2"></i> Update Guest
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

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
                                    <div class="alert alert-info text-center shadow-sm mb-0" role="alert"> {{-- Styled empty message --}}
                                        <i class="bi bi-info-circle me-2"></i> No guests found. Try adjusting your search or add a new guest.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($guests->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $guests->links('pagination::bootstrap-5') }}
        </div>
    @endif

    <div class="modal fade" id="createGuestModal" tabindex="-1" aria-labelledby="createGuestLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal"> {{-- Changed custom-modal to modal-dialog-centered --}}
            <div class="modal-content">
                <form id="createGuestForm" action="{{ route('guests.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white"> {{-- Styled modal header --}}
                        <h5 class="modal-title" id="createGuestLabel">Add New Guest</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="create_name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="create_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="create_tel">Phone</label>
                            <input type="text" name="tel" id="create_tel" class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel') }}">
                            @error('tel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="create_email">Email</label>
                            <input type="email" name="email" id="create_email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="create_gender">Gender</label>
                            <select name="gender" id="create_gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option> {{-- Added 'Other' option --}}
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Save Guest
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toast logic (success/error)
        const successToastEl = document.getElementById('successToast');
        const errorToastEl = document.getElementById('errorToast');
        if (successToastEl) {
            new bootstrap.Toast(successToastEl, { autohide: true, delay: 5000 }).show();
        }
        if (errorToastEl) {
            new bootstrap.Toast(errorToastEl, { autohide: true, delay: 5000 }).show();
        }

        // AJAX Guest Add (keep as is)
        const createForm = document.getElementById('createGuestForm');
        if(createForm) {
            createForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous validation feedback if any
                createForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                createForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                let formData = new FormData(createForm);

                fetch(createForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        // Handle validation errors returned from Laravel
                        for (const field in data.errors) {
                            const input = createForm.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.classList.add('invalid-feedback');
                                feedback.textContent = data.errors[field][0];
                                input.parentNode.appendChild(feedback);
                            }
                        }
                    } else if (data.success) {
                        // Success: hide modal, show toast, and reload page
                        const createModal = bootstrap.Modal.getInstance(document.getElementById('createGuestModal'));
                        if (createModal) {
                            createModal.hide();
                        }
                        // Simulate Laravel's session flash for toast
                        const successToastContainer = document.querySelector('.toast-container');
                        const successToastHtml = `
                            <div class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>${data.success}</div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        `;
                        successToastContainer.insertAdjacentHTML('beforeend', successToastHtml);
                        new bootstrap.Toast(successToastContainer.lastElementChild, { autohide: true, delay: 5000 }).show();

                        location.reload();
                    }
                })
                .catch(error => {
                    alert('An unexpected error occurred. Please try again.');
                    console.error('Error:', error);
                });
            });
        }
    });
</script>

@if ($errors->any())
    @if (old('name') && !session('guest_id_with_errors'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var createModal = new bootstrap.Modal(document.getElementById('createGuestModal'));
                createModal.show();
            });
        </script>
    @elseif(session('guest_id_with_errors'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var editModal = new bootstrap.Modal(document.getElementById("editGuestModal{{ session('guest_id_with_errors') }}"));
                editModal.show();
            });
        </script>
    @endif
@endif
@endsection
