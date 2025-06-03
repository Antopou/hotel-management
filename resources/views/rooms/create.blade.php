@extends('layouts.main')

@section('content')
<main class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card">
                    <div class="card-header card-title">
                        <h4 class="mb-0">Create New Room</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('rooms.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Room Name</label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="room_type_code" class="form-label">Room Type</label>
                                <select name="room_type_code" id="room_type_code"
                                        class="form-select @error('room_type_code') is-invalid @enderror" required>
                                    <option value="">-- Select Room Type --</option>
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type->room_type_code }}" {{ old('room_type_code') == $type->room_type_code ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_type_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Available" selected>Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Cleaning">Cleaning</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Room</button>
                            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
