<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $query = Guest::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('tel')) {
            $query->where('tel', 'like', '%' . $request->tel . '%');
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $guests = $query->latest()->paginate(10)->withQueryString();

        // API: Return paginated guest list as JSON
        if ($request->wantsJson()) {
            return response()->json($guests);
        }

        // Web: Return Blade view
        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'tel' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
        ]);

        $guest = Guest::create([
            'guest_code' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->tel,
            'gender' => $request->gender,
            'created_by' => 1,
        ]);

        // API: Return created guest JSON with 201 status
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Guest created successfully.',
                'data' => $guest
            ], 201);
        }

        // Web: Redirect to guest list with success message
        return redirect()->route('guests.index')->with('success', 'Guest created successfully.');
    }

    public function show(Request $request, Guest $guest)
    {
        // API: Return single guest as JSON
        if ($request->wantsJson()) {
            return response()->json($guest);
        }

        // Web: Return Blade view
        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'tel' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
        ]);

        $guest->update([
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->tel,
            'gender' => $request->gender,
            'modified_by' => 1,
        ]);

        // API: Return updated guest as JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Guest updated successfully.',
                'data' => $guest
            ]);
        }

        // Web: Redirect
        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }

    public function destroy(Request $request, Guest $guest)
    {
        $guest->delete();

        // API: Return success JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Guest deleted successfully.'
            ]);
        }

        // Web: Redirect
        return redirect()->route('guests.index')->with('success', 'Guest deleted.');
    }
}
