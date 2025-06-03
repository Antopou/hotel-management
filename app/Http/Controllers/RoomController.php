<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Support\Facades\Log;

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

    public function store(StoreRoomRequest $request) // Using StoreRoomRequest for validation
    {
        try {
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
                'created_by' => Auth::id(), // replaces auth()->id()
                'is_active' => true, // Assuming default active state
            ]);

            return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            Log::error("Error creating room: " . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Failed to create room. Please try again.');
        }
    }

    public function show(Room $room)
    {
        // This method is implicitly handled by the view modal in index.blade.php
        // If you were to have a dedicated show page, this would return that view.
        // For now, it's mostly for direct access or API.
        $room->load('roomType');
        return view('rooms.show', compact('room')); // Assuming you have a rooms/show.blade.php
    }

    // `edit()` method is no longer strictly necessary if using modals only
    // public function edit(Room $room)
    // {
    //     $roomTypes = RoomType::all();
    //     return view('rooms.edit', compact('room', 'roomTypes'));
    // }

    public function update(UpdateRoomRequest $request, Room $room) // Using UpdateRoomRequest for validation
    {
        try {
            $room->update([
                'name' => $request->name,
                'room_type_code' => $request->room_type_code,
                'status' => $request->status,
                'modified_by' => Auth::id(),
            ]);

            return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating room ID {$room->id}: " . $e->getMessage());
            // Flash a flag to re-open the edit modal if there's an error
            return redirect()->back()->withInput()->withErrors($e->getMessage())->with('error', 'Failed to update room. Please try again.')->with('edit_modal_errors', true);
        }
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete(); // If SoftDeletes trait is used on Room model, this will soft delete
            return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Error deleting room ID {$room->id}: " . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Failed to delete room. Please try again.');
        }
    }
}