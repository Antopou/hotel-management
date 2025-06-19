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

        // Eager load rooms and count them, plus available/occupied counts
        $roomTypes = $query
            ->withCount('rooms')
            ->with(['rooms' => function ($q) {
                $q->select('id', 'room_type_code', 'status');
            }])
            ->paginate(10);

        foreach ($roomTypes as $type) {
            $type->available_rooms = $type->rooms->where('status', 'available')->count();
            $type->occupied_rooms = $type->rooms->where('status', 'occupied')->count();
        }

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
            'size' => 'nullable|string|max:50',
            'bed_type' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'name', 'description', 'price_per_night', 'max_occupancy', 'size', 'bed_type'
        ]);
        $data['created_by'] = Auth::id() ?? 1;
        $data['is_active'] = true;

        // Boolean amenities
        $data['has_wifi'] = $request->has('has_wifi') ? 1 : 0;
        $data['has_tv'] = $request->has('has_tv') ? 1 : 0;
        $data['has_ac'] = $request->has('has_ac') ? 1 : 0;
        $data['has_breakfast'] = $request->has('has_breakfast') ? 1 : 0;
        $data['has_parking'] = $request->has('has_parking') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('room_types', 'public');
        }

        $roomType = RoomType::create($data);

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
            'size' => 'nullable|string|max:50',
            'bed_type' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'name', 'description', 'price_per_night', 'max_occupancy', 'size', 'bed_type'
        ]);

        // Boolean amenities
        $data['has_wifi'] = $request->has('has_wifi') ? 1 : 0;
        $data['has_tv'] = $request->has('has_tv') ? 1 : 0;
        $data['has_ac'] = $request->has('has_ac') ? 1 : 0;
        $data['has_breakfast'] = $request->has('has_breakfast') ? 1 : 0;
        $data['has_parking'] = $request->has('has_parking') ? 1 : 0;

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

