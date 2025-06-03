<?php
namespace App\Http\Controllers;

use App\Models\GuestReservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestReservation::with(['guest', 'room']);

        // Filter by guest name
        if ($request->filled('guest')) {
            $query->whereHas('guest', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->guest . '%');
            });
        }

        // Filter by specific check-in date
        if ($request->filled('checkin_date')) {
            $query->whereDate('checkin_date', $request->checkin_date);
        }

        // Filter by date range (from - to)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('checkin_date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('checkin_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('checkin_date', '<=', $request->date_to);
        }

        $reservations = $query->latest()->paginate(10)->withQueryString();
        $guests = Guest::all();
        $rooms = Room::all();
        return view('reservations.index', compact('reservations', 'guests', 'rooms'));
    }

    public function create()
    {
        $guests = Guest::all();
        $rooms = Room::all();
        return view('reservations.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'payment_method' => 'nullable|string',
            'number_of_guest' => 'required|integer|min:1',
        ]);

        GuestReservation::create([
            'reservation_code' => Str::uuid(),
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'rate' => $request->rate ?? 0,
            'total_payment' => $request->total_payment ?? 0,
            'payment_method' => $request->payment_method,
            'number_of_guest' => $request->number_of_guest,
            'is_checkin' => false,
            'created_by' => 1,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created.');
    }

    public function show(GuestReservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    public function edit(GuestReservation $reservation)
    {
        $guests = Guest::all();
        $rooms = Room::all();
        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    public function update(Request $request, GuestReservation $reservation)
    {
        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'payment_method' => 'nullable|string',
            'number_of_guest' => 'required|integer|min:1',
        ]);

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
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated.');
    }

    public function destroy(GuestReservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted.');
    }
    public function cancel(GuestReservation $reservation, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $reservation->update([
            'cancelled_date' => now(),
            'reason' => $request->reason,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation canceled.');
    }
}
