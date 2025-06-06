@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="bold m-0">Room Management</h3>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createRoomModal">
            <i class="bi bi-plus-circle me-2"></i> Add New Room
        </button>
    </div>

    {{-- Container for dynamic rooms list --}}
    <div id="rooms-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-4">
        <p>Loading rooms...</p>
    </div>

    {{-- Pagination placeholder --}}
    <nav>
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
</div>

{{-- Include modals (make sure these partials exist and include all modals) --}}
@include('rooms._modals')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomsContainer = document.getElementById('rooms-container');
    const paginationContainer = document.getElementById('pagination');

    function fetchRooms(page = 1) {
        roomsContainer.innerHTML = '<p>Loading rooms...</p>';
        fetch(`{{ url('api/rooms') }}?page=${page}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Optional if needed
            },
            credentials: 'same-origin' // include cookies for auth sessions
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not OK');
            return res.json();
        })
        .then(data => {
            if (!data.rooms || data.rooms.data.length === 0) {
                roomsContainer.innerHTML = '<p>No rooms found.</p>';
                paginationContainer.innerHTML = '';
                return;
            }

            // Render rooms cards
            roomsContainer.innerHTML = data.rooms.data.map(room => {
                const roomTypeName = room.room_type ? room.room_type.name : 'N/A';
                const imageUrl = room.room_type && room.room_type.image
                    ? `{{ asset('storage') }}/${room.room_type.image}`
                    : `{{ asset('images/room_types/default.jpg') }}`;

                return `
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 card-hover">
                        <img src="${imageUrl}" class="card-img-top" alt="${roomTypeName}" style="height: 180px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary mb-1">${room.name}</h5>
                            <p class="card-text text-muted small mb-1">
                                <strong>Room Type:</strong> ${roomTypeName}
                            </p>
                            <p class="card-text small">
                                <strong>Status:</strong>
                                <span class="badge bg-${
                                    room.status === 'Available' ? 'success' :
                                    room.status === 'Occupied' ? 'danger' :
                                    room.status === 'Cleaning' ? 'warning' : 'secondary'
                                } text-uppercase">${room.status}</span>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewRoomModal${room.id}">
                                <i class="bi bi-eye-fill"></i> View
                            </button>
                            <button class="btn btn-outline-secondary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editRoomModal"
                                data-room-id="${room.id}"
                                data-room-name="${room.name}"
                                data-room-type-code="${room.room_type_code}"
                                data-room-status="${room.status}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteRoomModal"
                                data-room-id="${room.id}"
                                data-room-name="${room.name}">
                                <i class="bi bi-trash-fill"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>`;
            }).join('');

            renderPagination(data.rooms);
            initModalEventListeners();
        })
        .catch(err => {
            roomsContainer.innerHTML = '<p>Error loading rooms.</p>';
            console.error(err);
        });
    }

    function renderPagination(rooms) {
        let html = '';

        if (rooms.current_page > 1) {
            html += `<li class="page-item"><a href="#" class="page-link" data-page="${rooms.current_page - 1}">&laquo; Prev</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>`;
        }

        for(let i = 1; i <= rooms.last_page; i++) {
            html += `<li class="page-item ${i === rooms.current_page ? 'active' : ''}"><a href="#" class="page-link" data-page="${i}">${i}</a></li>`;
        }

        if (rooms.current_page < rooms.last_page) {
            html += `<li class="page-item"><a href="#" class="page-link" data-page="${rooms.current_page + 1}">Next &raquo;</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>`;
        }

        paginationContainer.innerHTML = html;

        paginationContainer.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                fetchRooms(page);
            });
        });
    }

    function initModalEventListeners() {
        // Example: Setup event listener for Edit modal to populate data
        const editRoomModal = document.getElementById('editRoomModal');
        if (!editRoomModal) return;

        editRoomModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const roomId = button.getAttribute('data-room-id');
            const roomName = button.getAttribute('data-room-name');
            const roomTypeCode = button.getAttribute('data-room-type-code');
            const roomStatus = button.getAttribute('data-room-status');

            editRoomModal.querySelector('#editRoomForm').action = `/rooms/${roomId}`;
            editRoomModal.querySelector('#editRoomId').value = roomId;
            editRoomModal.querySelector('#editName').value = roomName;
            editRoomModal.querySelector('#editRoomTypeCode').value = roomTypeCode;
            editRoomModal.querySelector('#editStatus').value = roomStatus;

            // Clear errors or other UI updates here if needed
        });

        // Similarly for delete modal or view modal if needed
        const deleteRoomModal = document.getElementById('deleteRoomModal');
        if (deleteRoomModal) {
            deleteRoomModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const roomId = button.getAttribute('data-room-id');
                const roomName = button.getAttribute('data-room-name');

                deleteRoomModal.querySelector('#deleteRoomForm').action = `/rooms/${roomId}`;
                deleteRoomModal.querySelector('#deleteRoomName').innerText = roomName;
            });
        }
    }

    fetchRooms();
});
</script>
@endsection
