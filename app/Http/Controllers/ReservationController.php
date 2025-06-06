<?php

namespace App\Http\Controllers;

use App\Models\GuestReservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestReservation::with(['guest', 'room']);

        if ($request->filled('guest')) {
            $query->whereHas('guest', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->guest . '%');
            });
        }
        if ($request->filled('checkin_date')) {
            $query->whereDate('checkin_date', $request->checkin_date);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('checkin_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('checkin_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('checkin_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(10)->withQueryString();
        $guests = Guest::all();
        $rooms = Room::all();

        if ($request->wantsJson()) {
            return response()->json([
                'reservations' => $reservations,
                'guests' => $guests,
                'rooms' => $rooms,
            ]);
        }

        return view('reservations.index', compact('reservations', 'guests', 'rooms'));
    }

    public function create(Request $request)
    {
        $guests = Guest::all();
        $rooms = Room::all();

        if ($request->wantsJson()) {
            return response()->json([
                'guests' => $guests,
                'rooms' => $rooms,
            ]);
        }

        return view('reservations.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
            'status' => 'nullable|string|in:pending,confirmed,checked-in,checked-out,cancelled',
        ]);

        // Check for double-booking
        $conflict = GuestReservation::where('room_code', $request->room_code)
            ->where(function ($query) use ($request) {
                $query->whereBetween('checkin_date', [$request->checkin_date, $request->checkout_date])
                      ->orWhereBetween('checkout_date', [$request->checkin_date, $request->checkout_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('checkin_date', '<=', $request->checkin_date)
                            ->where('checkout_date', '>=', $request->checkout_date);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->exists();

        if ($conflict) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Room is already booked for the selected dates.',
                ], 422);
            }
            return back()->withInput()->withErrors(['room_code' => 'Room is already booked for the selected dates.']);
        }

        $reservation = GuestReservation::create([
            'reservation_code' => Str::uuid(),
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'number_of_guest' => $request->number_of_guest,
            'status' => $request->status ?? 'pending',
            'is_checkin' => false,
            'created_by' => 1,
        ]);

        // --- Set Room as reserved ---
        $room = Room::where('room_code', $request->room_code)->first();
        if ($room) $room->update(['status' => 'reserved']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservation created.',
                'reservation' => $reservation,
            ], 201);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation created.');
    }

    public function show(Request $request, GuestReservation $reservation)
    {
        if ($request->wantsJson()) {
            return response()->json($reservation->load(['guest', 'room']));
        }

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Request $request, GuestReservation $reservation)
    {
        $guests = Guest::all();
        $rooms = Room::all();

        if ($request->wantsJson()) {
            return response()->json([
                'reservation' => $reservation->load(['guest', 'room']),
                'guests' => $guests,
                'rooms' => $rooms,
            ]);
        }

        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    public function update(Request $request, GuestReservation $reservation)
    {
        $validated = $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'payment_method' => 'nullable|string',
            'number_of_guest' => 'required|integer|min:1',
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'checked-in', 'cancelled', 'no-show', 'checked-out'])],
            'is_checkin' => 'nullable|boolean',
        ]);

        // Prevent editing to double-booked dates
        $conflict = GuestReservation::where('room_code', $request->room_code)
            ->where('id', '<>', $reservation->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('checkin_date', [$request->checkin_date, $request->checkout_date])
                      ->orWhereBetween('checkout_date', [$request->checkin_date, $request->checkout_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('checkin_date', '<=', $request->checkin_date)
                            ->where('checkout_date', '>=', $request->checkout_date);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked-in'])
            ->exists();

        if ($conflict) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Room is already booked for the selected dates.',
                ], 422);
            }
            return back()->withInput()->withErrors(['room_code' => 'Room is already booked for the selected dates.']);
        }

        $isCheckin = $request->has('is_checkin') && $request->input('is_checkin');
        $status = $request->input('status', $reservation->status);

        if ($isCheckin && $reservation->status !== 'checked-in') {
            $status = 'checked-in';
        } elseif (!$isCheckin && $reservation->status === 'checked-in') {
            $status = 'pending'; // or previous status
        }

        $reservation->update([
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'rate' => $request->rate ?? 0,
            'total_payment' => $request->total_payment ?? 0,
            'payment_method' => $request->payment_method,
            'number_of_guest' => $request->number_of_guest,
            'modified_by' => 1,
            'is_checkin' => $isCheckin,
            'status' => $status,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservation updated.',
                'reservation' => $reservation,
            ]);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation updated.');
    }

    public function destroy(Request $request, GuestReservation $reservation)
    {
        $reservation->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservation deleted.'
            ]);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted.');
    }

    public function cancel(Request $request, GuestReservation $reservation)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        $reservation->update([
            'cancelled_date' => now(),
            'reason' => $request->reason,
            'status' => 'cancelled',
        ]);

        // --- Set Room as available again ---
        $room = Room::where('room_code', $reservation->room_code)->first();
        if ($room) $room->update(['status' => 'available']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Reservation canceled.'
            ]);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation canceled.');
    }
}
