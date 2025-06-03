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

        // Filter by guest name
        if ($request->filled('guest')) {
            $query->whereHas('guest', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->guest . '%');
            });
        }

        // Filter by room name
        if ($request->filled('room')) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->room . '%');
            });
        }

        // Filter by specific check-in date
        if ($request->filled('checkin_date')) {
            $query->whereDate('checkin_date', $request->checkin_date);
        }

        $checkins = $query->latest()->paginate(10)->withQueryString();
        $guests = Guest::all();
        $rooms = Room::all();

        return view('checkins.index', compact('checkins', 'guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'reservation_ref' => 'nullable|exists:guest_reservation,reservation_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'nullable|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
            'rate' => 'required|numeric|min:0',
            'total_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        GuestCheckin::create([
            'checkin_code' => Str::uuid(),
            'reservation_ref' => $request->reservation_ref,
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'rate' => $request->rate,
            'total_payment' => $request->total_payment ?? 0,
            'payment_method' => $request->payment_method,
            'number_of_guest' => $request->number_of_guest,
            'is_checkout' => $request->has('is_checkout'),
            'created_by' => 1,
        ]);

        return redirect()->route('checkins.index')->with('success', 'Guest checked in.');
    }

    public function show(GuestCheckin $checkin)
    {
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
        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
            'rate' => 'required|numeric|min:0',
            'total_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        $checkin->update([
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'reservation_ref' => $request->reservation_ref,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'rate' => $request->rate,
            'total_payment' => $request->total_payment,
            'payment_method' => $request->payment_method,
            'number_of_guest' => $request->number_of_guest,
            'is_checkout' => $request->has('is_checkout'),
            'modified_by' => 1,
        ]);

        return redirect()->route('checkins.index')->with('success', 'Check-in updated.');
    }

    public function destroy(GuestCheckin $checkin)
    {
        $checkin->delete();
        return redirect()->route('checkins.index')->with('success', 'Check-in record deleted.');
    }
}
