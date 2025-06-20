<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckin;
use App\Models\GuestReservation;
use App\Models\Guest;
use App\Models\Room;
use App\Models\GuestFolio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestCheckin::with(['guest', 'room.roomType', 'folio']);

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

        return view('checkins.index', compact('checkins', 'guests', 'rooms'));
    }

    public function store(Request $request)
    {
        // If reservation_id is present, auto-fill fields from reservation
        if ($request->filled('reservation_id')) {
            $reservation = GuestReservation::find($request->reservation_id);
            if (!$reservation) {
                return back()->with('error', 'Reservation not found.');
            }

            // Optionally, check if already checked in
            if ($reservation->status === 'checked-in' || $reservation->is_checkin) {
                return back()->with('error', 'This reservation is already checked in.');
            }

            // Fill request data from reservation
            $request->merge([
                'guest_code' => $reservation->guest_code,
                'room_code' => $reservation->room_code,
                'reservation_ref' => $reservation->reservation_code,
                'checkin_date' => $reservation->checkin_date,
                'checkout_date' => $reservation->checkout_date,
                'number_of_guest' => $reservation->number_of_guest,
            ]);
        }

        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'reservation_ref' => 'nullable|exists:guest_reservations,reservation_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'nullable|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000', // <-- validate notes
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
            'note' => $request->notes, // <-- Save notes to note column
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

        // If reservation_id was used, update reservation status
        if ($request->filled('reservation_id') && isset($reservation)) {
            $reservation->status = 'checked-in';
            $reservation->is_checkin = true;
            $reservation->save();
        }

        // Detect redirect target
        $redirectTo = $request->input('redirect_to');
        $page = $request->input('page', 1);
        if ($redirectTo === 'front-desk') {
            return redirect()->route('front-desk.index', array_merge($request->except(['_token', 'redirect_to']), ['page' => $page]))
                ->with('success', 'Check-in successful!');
        }

        // fallback to default
        return redirect()->route('checkins.index')->with('success', 'Check-in successful!');
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
        if ($request->has('is_checkout') && $request->keys() === ['_token', '_method', 'is_checkout']) {
            $checkin->is_checkout = true;
            // Only update checkout_date if it's not set or is in the future
            if (!$checkin->checkout_date) {
                $checkin->checkout_date = now();
            }
            $checkin->modified_by = 1;
            $checkin->save();

            $room = Room::where('room_code', $checkin->room_code)->first();
            if ($room) $room->update(['status' => 'cleaning']);

            // --- Create GuestFolio if not exists ---
            if (!$checkin->folio) {
                $folioCode = 'FL-' . date('Ymd') . '-' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                $folio = GuestFolio::create([
                    'folio_code' => $folioCode,
                    'guest_code' => $checkin->guest_code,
                    'room_code' => $checkin->room_code,
                    'checkin_code' => $checkin->checkin_code,
                    'total_amount' => 0,
                    'paid_amount' => 0,
                    'status' => 'open',
                    'currency' => 'USD'
                ]);

                // --- Calculate Room Charge Correctly ---
                $roomType = optional(optional($room)->roomType);
                $pricePerNight = $roomType->price_per_night ?? 0;
                $nights = \Carbon\Carbon::parse($checkin->checkin_date)->diffInDays(\Carbon\Carbon::parse($checkin->checkout_date));
                if ($nights < 1) $nights = 1; // At least 1 night
                $totalRoomCharge = $pricePerNight * $nights;

                \App\Models\GuestFolioItem::create([
                    'folio_id'    => $folio->id,
                    'type'        => 'charge',
                    'description' => 'Room Charge',
                    'amount'      => $totalRoomCharge,
                    'posted_at'   => now(),
                ]);
                $folio->recalculateTotals();
            } else {
                $folio = $checkin->folio;
            }

            // Redirect to folio page for payment/settlement
            return redirect()->route('front-desk.folios.show', $folio->folio_code)
                ->with('success', 'Guest checked out successfully. Please settle the bill.');
        }

        $request->validate([
            'guest_code' => 'required|exists:guests,guest_code',
            'room_code' => 'required|exists:rooms,room_code',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'number_of_guest' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000', // <-- validate notes
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
            'note' => $request->notes, // <-- Save notes to note column
        ]);

        if (!$wasCheckedOut && $checkin->is_checkout) {
            $hasOtherActive = GuestCheckin::where('room_code', $checkin->room_code)
                ->where('is_checkout', false)
                ->where('id', '!=', $checkin->id)
                ->exists();
            if (!$hasOtherActive) {
                $room = Room::where('room_code', $checkin->room_code)->first();
                if ($room) {
                    // Check for next reservation
                    $nextReservation = $room->reservations()
                        ->where('checkin_date', '>=', now())
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->orderBy('checkin_date')
                        ->first();

                    if ($nextReservation) {
                        $room->update(['status' => 'reserved']);
                    } else {
                        $room->update(['status' => 'available']);
                    }
                }
            }
        }

        if ($oldRoomCode !== $checkin->room_code) {
            $hasOtherActive = GuestCheckin::where('room_code', $oldRoomCode)
                ->where('is_checkout', false)
                ->exists();
            if (!$hasOtherActive) {
                $room = Room::where('room_code', $oldRoomCode)->first();
                if ($room) $room->update(['status' => 'available']);
            }
            $room = Room::where('room_code', $checkin->room_code)->first();
            if ($room) $room->update(['status' => 'occupied']);
        }

        return redirect()->route('checkins.index')->with('success', 'Check-in updated.');
    }



    public function destroy(GuestCheckin $checkin)
    {
        $room_code = $checkin->room_code;
        $checkin->delete();

        $hasOtherActive = GuestCheckin::where('room_code', $room_code)
            ->where('is_checkout', false)
            ->exists();

        if (!$hasOtherActive) {
            $room = Room::where('room_code', $room_code)->first();
            if ($room) $room->update(['status' => 'available']);
        }

        return redirect()->route('checkins.index')->with('success', 'Check-in record deleted.');
    }

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
            return back()->with('error', 'Already checked in.');
        }

        $reservation->status = 'checked-in';
        $reservation->is_checkin = true;
        $reservation->save();

        $room = Room::where('room_code', $reservation->room_code)->first();
        if ($room && $room->status !== 'occupied') {
            $room->update(['status' => 'occupied']);
        }

        return back()->with('success', 'Check-in successful for ' . ($reservation->guest->name ?? ''));
    }

}
