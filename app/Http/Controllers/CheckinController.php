<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckin;
use App\Models\GuestReservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestCheckin::with(['guest', 'room']);

        if ($request->filled('guest')) {
            $query->whereHas('guest', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->guest . '%');
            });
        }
        if ($request->filled('room')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->room . '%');
            });
        }
        if ($request->filled('checkin_date')) {
            $query->whereDate('checkin_date', $request->checkin_date);
        }

        $checkins = $query->latest()->paginate(10)->withQueryString();
        $guests = Guest::all();
        $rooms = Room::all();

        // Return JSON if API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $checkins
            ]);
        }

        return view('checkins.index', compact('checkins', 'guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'reservation_ref' => 'nullable|exists:guest_reservations,reservation_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'nullable|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
        ]);

        $checkin = GuestCheckin::create([
            'checkin_code' => Str::uuid(),
            'reservation_ref' => $request->reservation_ref,
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'number_of_guest' => $request->number_of_guest,
            'is_checkout' => $request->has('is_checkout'),
            'created_by' => 1,
        ]);

        // Set Room as occupied if no other ongoing checkins
        $room = Room::where('room_code', $request->room_code)->first();
        if ($room) {
            $hasActive = GuestCheckin::where('room_code', $request->room_code)
                ->where('is_checkout', false)
                ->where('id', '!=', $checkin->id)
                ->exists();
            if (!$hasActive) {
                $room->update(['status' => 'occupied']);
            }
        }

        // API: Return JSON response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Guest checked in.',
                'data' => $checkin
            ], 201);
        }

        // Web: Redirect
        return redirect()->route('checkins.index')->with('success', 'Guest checked in.');
    }

    public function show(Request $request, GuestCheckin $checkin)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $checkin->load(['guest', 'room'])
            ]);
        }

        return view('checkins.show', compact('checkin'));
    }

    public function edit(GuestCheckin $checkin)
    {
        $guests = Guest::all();
        $rooms = Room::all();
        $reservations = GuestReservation::all();
        return view('checkins.edit', compact('checkin', 'guests', 'rooms', 'reservations'));
    }

    public function update(Request $request, GuestCheckin $checkin)
    {
        $validated = $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
        ]);

        $oldRoomCode = $checkin->room_code;
        $wasCheckedOut = $checkin->is_checkout;

        $checkin->update([
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'reservation_ref' => $request->reservation_ref,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'number_of_guest' => $request->number_of_guest,
            'is_checkout' => $request->has('is_checkout'),
            'modified_by' => 1,
        ]);

        // If checked out, set Room as available if no other active checkins
        if (!$wasCheckedOut && $checkin->is_checkout) {
            $hasOtherActive = GuestCheckin::where('room_code', $checkin->room_code)
                ->where('is_checkout', false)
                ->where('id', '!=', $checkin->id)
                ->exists();
            if (!$hasOtherActive) {
                $room = Room::where('room_code', $checkin->room_code)->first();
                if ($room) $room->update(['status' => 'available']);
            }
        }

        // If room code was changed, update old room too!
        if ($oldRoomCode !== $checkin->room_code) {
            $hasOtherActive = GuestCheckin::where('room_code', $oldRoomCode)
                ->where('is_checkout', false)
                ->exists();
            if (!$hasOtherActive) {
                $room = Room::where('room_code', $oldRoomCode)->first();
                if ($room) $room->update(['status' => 'available']);
            }
            // Mark new room as occupied
            $room = Room::where('room_code', $checkin->room_code)->first();
            if ($room) $room->update(['status' => 'occupied']);
        }

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Check-in updated.',
                'data' => $checkin
            ]);
        }

        return redirect()->route('checkins.index')->with('success', 'Check-in updated.');
    }

    public function destroy(Request $request, GuestCheckin $checkin)
    {
        $room_code = $checkin->room_code;
        $checkin->delete();

        // Set room as available if no other active checkins
        $hasOtherActive = GuestCheckin::where('room_code', $room_code)
            ->where('is_checkout', false)
            ->exists();

        if (!$hasOtherActive) {
            $room = Room::where('room_code', $room_code)->first();
            if ($room) $room->update(['status' => 'available']);
        }

        // API: Return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Check-in record deleted.'
            ]);
        }

        return redirect()->route('checkins.index')->with('success', 'Check-in record deleted.');
    }

    // --- These are web only, but you can add API versions if needed ---
    public function checkinPage(Request $request)
    {
        $today = now()->startOfDay();
        $reservations = GuestReservation::with(['guest', 'room'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDate('checkin_date', '<=', $today)
            ->where('is_checkin', false)
            ->orderBy('checkin_date')
            ->get();

        return view('reservations.checkin', compact('reservations'));
    }

    public function doCheckin(Request $request, $id)
    {
        $reservation = GuestReservation::findOrFail($id);
        if ($reservation->status === 'checked-in') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Already checked in.'], 422);
            }
            return back()->with('error', 'Already checked in.');
        }

        $reservation->status = 'checked-in';
        $reservation->is_checkin = true;
        $reservation->save();

        $room = Room::where('room_code', $reservation->room_code)->first();
        if ($room && $room->status !== 'occupied') {
            $room->update(['status' => 'occupied']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Check-in successful',
                'reservation' => $reservation
            ]);
        }

        return back()->with('success', 'Check-in successful for ' . ($reservation->guest->name ?? ''));
    }
}
