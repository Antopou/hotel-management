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

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json($roomTypes);
        }

        // Web: Return Blade view
        return view('room-types.index', compact('roomTypes'));
    }

    public function create(Request $request)
    {
        // API: Return nothing or schema if needed
        if ($request->wantsJson()) {
            return response()->json([
                'fields' => [
                    'name', 'description', 'price_per_night', 'max_occupancy', 'image'
                ]
            ]);
        }

        // Web: Return Blade view
        return view('room-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        $roomType = RoomType::create($data);

        $roomType->update([
            'room_type_code' => 'RT-' . strtoupper(Str::random(5)),
        ]);

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Room Type created.',
                'roomType' => $roomType,
            ], 201);
        }

        // Web: Redirect
        return redirect()->route('room-types.index')->with('success', 'Room Type created.');
    }

    public function edit(Request $request, RoomType $roomType)
    {
        // API: Return roomType JSON
        if ($request->wantsJson()) {
            return response()->json($roomType);
        }

        // Web: Return Blade view
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
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

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Room Type updated.',
                'roomType' => $roomType,
            ]);
        }

        // Web: Redirect
        return redirect()->route('room-types.index')->with('success', 'Room Type updated.');
    }

    public function destroy(Request $request, RoomType $roomType)
    {
        $roomType->delete();

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Room Type deleted.'
            ]);
        }

        // Web: Redirect
        return redirect()->route('room-types.index')->with('success', 'Room Type deleted.');
    }
}
