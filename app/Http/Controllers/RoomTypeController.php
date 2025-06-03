<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomType::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $roomTypes = $query->latest()->paginate(10);
        return view('room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_night', 'max_occupancy']);
        $data['created_by'] = Auth::id() ?? 1;
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room_types', 'public');
        }

        // Create the room type first to get ID
        $roomType = RoomType::create($data);

        // Update room_type_code based on ID
        $roomType->update([
            'room_type_code' => 'RT-' . strtoupper(Str::random(5)),
        ]);

        return redirect()->route('room-types.index')->with('success', 'Room Type created.');
    }

    public function edit(RoomType $roomType)
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price_per_night', 'max_occupancy']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room_types', 'public');
        }

        $roomType->update($data);

        return redirect()->route('room-types.index')->with('success', 'Room Type updated.');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return redirect()->route('room-types.index')->with('success', 'Room Type deleted.');
    }
}

