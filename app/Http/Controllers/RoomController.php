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

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('room_type_code')) {
            $query->where('room_type_code', $request->room_type_code);
        }

        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        $rooms = $query->orderBy($sortBy, $direction)
            ->paginate(10)
            ->appends($request->query());

        $roomTypes = RoomType::all();

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

            Room::create([
                'room_code' => $roomCode,
                'name' => $request->name,
                'room_type_code' => $request->room_type_code,
                // Always save as lowercase
                'status' => strtolower($request->status),
                'created_by' => Auth::id(),
                'is_active' => true,
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
        return view('rooms.show', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            $room->update([
                'name' => $request->name,
                'room_type_code' => $request->room_type_code,
                // Always save as lowercase
                'status' => strtolower($request->status),
                'modified_by' => Auth::id(),
            ]);

            return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating room ID {$room->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e->getMessage())->with('error', 'Failed to update room. Please try again.')->with('edit_modal_errors', true);
        }
    }

    public function updateStatus(Request $request, Room $room)
    {
        // Accept mixed case and validate after lowercasing
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $allowed = ['available', 'occupied', 'cleaning', 'maintenance'];
                    if (!in_array(strtolower($value), $allowed)) {
                        $fail('The selected status is invalid.');
                    }
                }
            ],
        ]);

        try {
            $room->update([
                'status' => strtolower($validated['status']),
                'modified_by' => Auth::id(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Room status updated successfully.', 'room' => $room], 200);
            }

            return redirect()->back()->with('success', 'Room status updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating room status for ID {$room->id}: " . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Failed to update room status.', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to update room status. Please try again.');
        }
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Error deleting room ID {$room->id}: " . $e->getMessage());
            return redirect()->route('rooms.index')->with('error', 'Failed to delete room. Please try again.');
        }
    }
}
