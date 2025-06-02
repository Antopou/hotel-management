<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'id');
        $direction = $request->get('direction', 'asc');

        $query = Room::with('roomType');

        // Search by room name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by room type
        if ($request->filled('room_type_code')) {
            $query->where('room_type_code', $request->room_type_code);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy($sortBy, $direction)
            ->paginate(10)
            ->appends($request->query()); // preserve query parameters

        $roomTypes = RoomType::all();

        return view('rooms.index', compact('rooms', 'roomTypes', 'sortBy', 'direction'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'room_type_code' => 'required|exists:room_types,room_type_code',
            'status' => 'required|string|in:Available,Occupied,Cleaning,Maintenance',

        ]);

        // Generate next room code
        $lastRoom = Room::orderBy('id', 'desc')->first();
        $nextNumber = 1;

        if ($lastRoom && preg_match('/ROOM-(\d+)/', $lastRoom->room_code, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }

        $roomCode = 'ROOM-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Room::create([
            'room_code' => $roomCode,
            'name' => $request->name,
            'room_type_code' => $request->room_type_code,
            'status' => $request->status,
            'created_by' => 1,
            'is_active' => true,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load('roomType');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'room_type_code' => 'required|exists:room_types,room_type_code',
            'status' => 'required|string|in:Available,Occupied,Cleaning,Maintenance',
        ]);

        $room->update([
            'name' => $request->name,
            'room_type_code' => $request->room_type_code,
            'status' => $request->status,
            'modified_by' => 1,
        ]);


        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
