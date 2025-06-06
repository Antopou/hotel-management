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

        // API: Return paginated room list as JSON
        if ($request->wantsJson()) {
            return response()->json([
                'rooms' => $rooms,
                'roomTypes' => $roomTypes,
                'sortBy' => $sortBy,
                'direction' => $direction,
            ]);
        }

        // Web: Return Blade view
        return view('rooms.index', compact('rooms', 'roomTypes', 'sortBy', 'direction'));
    }

    public function store(StoreRoomRequest $request)
    {
        try {
            $lastRoom = Room::orderBy('id', 'desc')->first();
            $nextNumber = 1;

            if ($lastRoom && preg_match('/ROOM-(\d+)/', $lastRoom->room_code, $matches)) {
                $nextNumber = (int)$matches[1] + 1;
            }

            $roomCode = 'ROOM-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $room = Room::create([
                'room_code' => $roomCode,
                'name' => $request->name,
                'room_type_code' => $request->room_type_code,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            // API: Return JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Room created successfully.',
                    'room' => $room,
                ], 201);
            }

            // Web: Redirect
            return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            Log::error("Error creating room: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to create room.',
                    'details' => $e->getMessage(),
                ], 500);
            }
            return redirect()->route('rooms.index')->with('error', 'Failed to create room. Please try again.');
        }
    }

    public function show(Request $request, Room $room)
    {
        $room->load('roomType');

        // API: Return room JSON
        if ($request->wantsJson()) {
            return response()->json($room);
        }

        // Web: Show blade view
        return view('rooms.show', compact('room'));
    }

    // If you want to keep the edit form (not just modal)
    // public function edit(Request $request, Room $room)
    // {
    //     $roomTypes = RoomType::all();
    //     if ($request->wantsJson()) {
    //         return response()->json([
    //             'room' => $room->load('roomType'),
    //             'roomTypes' => $roomTypes,
    //         ]);
    //     }
    //     return view('rooms.edit', compact('room', 'roomTypes'));
    // }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            $room->update([
                'name' => $request->name,
                'room_type_code' => $request->room_type_code,
                'status' => $request->status,
                'modified_by' => Auth::id(),
            ]);

            // API: Return JSON response
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Room updated successfully.',
                    'room' => $room,
                ]);
            }

            // Web: Redirect
            return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating room ID {$room->id}: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to update room.',
                    'details' => $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->withInput()->withErrors($e->getMessage())->with('error', 'Failed to update room. Please try again.')->with('edit_modal_errors', true);
        }
    }

    public function destroy(Request $request, Room $room)
    {
        try {
            $room->delete(); // Soft delete if SoftDeletes trait is used

            // API: Return JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Room deleted successfully.'
                ]);
            }

            // Web: Redirect
            return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Error deleting room ID {$room->id}: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to delete room.',
                    'details' => $e->getMessage(),
                ], 500);
            }
            return redirect()->route('rooms.index')->with('error', 'Failed to delete room. Please try again.');
        }
    }
}
