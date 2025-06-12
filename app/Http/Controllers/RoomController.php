<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest; // You might create a new request for status updates if validation is different
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
        $room->load('roomType');
        return view('rooms.show', compact('room')); // Assuming you have a rooms/show.blade.php
    }

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
            return redirect()->back()->withInput()->withErrors($e->getMessage())->with('error', 'Failed to update room. Please try again.')->with('edit_modal_errors', true);
        }
    }

    // --- NEW METHOD FOR STATUS UPDATE ---
    public function updateStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Available,Occupied,Cleaning,Maintenance'],
        ]);

        try {
            // Always store status as lowercase in the database
            $room->update([
                'status' => strtolower($validated['status']),
                'modified_by' => Auth::id(),
            ]);

            // Respond with success. For AJAX, a JSON response is common.
            // For a direct form submission, redirect back.
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Room status updated successfully.', 'room' => $room], 200);
            }

            return redirect()->back()->with('success', 'Room status updated successfully.');

        } catch (\Exception $e) {
            Log::error("Error updating room status for ID {$room->id}: " . $e->getMessage());
            // For AJAX, return a JSON error.
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Failed to update room status.', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to update room status. Please try again.');
        }
    }
    // --- END NEW METHOD ---

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