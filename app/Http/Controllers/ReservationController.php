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
        $query = GuestReservation::with(['guest', 'room.roomType']); // <-- updated

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

        // Calculate stats
        $pendingCount = GuestReservation::where('status', 'pending')->count();
        $confirmedCount = GuestReservation::where('status', 'confirmed')->count();
        $todayArrivals = GuestReservation::whereDate('checkin_date', now()->toDateString())->count();

        // Calculate number_of_nights and total_amount for each reservation using room type rate
        $reservations->getCollection()->transform(function ($reservation) {
            if ($reservation->checkin_date && $reservation->checkout_date) {
                $reservation->number_of_nights = \Carbon\Carbon::parse($reservation->checkin_date)
                    ->diffInDays(\Carbon\Carbon::parse($reservation->checkout_date));
            } else {
                $reservation->number_of_nights = 0;
            }
            $rate = $reservation->room->roomType->price_per_night ?? 0;
            $reservation->total_amount = $rate * $reservation->number_of_nights;
            return $reservation;
        });

        $guests = Guest::all();
        $rooms = Room::with('roomType')->get();

        return view('reservations.index', compact(
            'reservations', 'guests', 'rooms',
            'pendingCount', 'confirmedCount', 'todayArrivals'
        ));
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
            'number_of_guest' => 'required|integer|min:1',
            'status' => 'nullable|string|in:pending,confirmed,checked-in,checked-out,cancelled',
            'notes' => 'nullable|string|max:1000',
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
            return back()->withInput()->withErrors(['room_code' => 'Room is already booked for the selected dates.']);
        }

        // After validation and before creating the reservation
        $room = Room::with('roomType')->where('room_code', $request->room_code)->first();
        $rate = ($room && $room->roomType) ? $room->roomType->price_per_night : 0;
        $number_of_nights = 0;
        if ($request->checkin_date && $request->checkout_date) {
            $number_of_nights = \Carbon\Carbon::parse($request->checkin_date)
                ->diffInDays(\Carbon\Carbon::parse($request->checkout_date));
        }
        $total_amount = $rate * $number_of_nights;

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
            'total_amount' => $total_amount,
            'note' => $request->notes, // <-- Save notes to note column
        ]);

        // --- Set Room as reserved ONLY if currently available ---
        $room = Room::where('room_code', $request->room_code)->first();
        if ($room && strtolower($room->status) === 'available') {
            $room->update(['status' => 'reserved']);
        }
        // If status is "occupied", "cleaning", or "maintenance", leave as is.

        $page = $request->input('page', 1);
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'front-desk') {
            return redirect()->route('front-desk.index', array_merge($request->except(['_token', 'redirect_to']), ['page' => $page]))
                ->with('success', 'Reservation created.');
        }

        // Default: go back to reservations page
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
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'checked-in', 'cancelled', 'no-show', 'checked-out'])],
            'is_checkin' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
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
            return back()->withInput()->withErrors(['room_code' => 'Room is already booked for the selected dates.']);
        }

        $isCheckin = $request->has('is_checkin') && $request->input('is_checkin');
        $status = $request->input('status', $reservation->status);

        if ($isCheckin && $reservation->status !== 'checked-in') {
            $status = 'checked-in';
        } elseif (!$isCheckin && $reservation->status === 'checked-in') {
            $status = 'pending'; // or previous status
        }

        $room = Room::with('roomType')->where('room_code', $request->room_code)->first();
        $rate = ($room && $room->roomType) ? $room->roomType->price_per_night : 0;
        $number_of_nights = 0;
        if ($request->checkin_date && $request->checkout_date) {
            $number_of_nights = \Carbon\Carbon::parse($request->checkin_date)
                ->diffInDays(\Carbon\Carbon::parse($request->checkout_date));
        }
        $total_amount = $rate * $number_of_nights;

        $reservation->update([
            'guest_code' => $request->guest_code,
            'room_code' => $request->room_code,
            'checkin_date' => $request->checkin_date,
            'checkout_date' => $request->checkout_date,
            'rate' => $rate,
            'total_payment' => $request->total_payment ?? 0,
            'payment_method' => $request->payment_method,
            'number_of_guest' => $request->number_of_guest,
            'modified_by' => 1,
            'is_checkin' => $isCheckin,
            'status' => $status,
            'total_amount' => $total_amount,
            'note' => $request->notes, // <-- Save notes to note column
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
            'status' => 'cancelled',
        ]);

        // --- Set Room as available again ---
        $room = Room::where('room_code', $reservation->room_code)->first();
        if ($room) $room->update(['status' => 'available']);

        return redirect()->route('reservations.index')->with('success', 'Reservation canceled.');
    }

    public function confirm(Request $request, GuestReservation $reservation)
    {
        $reservation->status = 'confirmed';
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Reservation confirmed.');
    }
}

