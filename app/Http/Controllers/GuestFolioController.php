<?php

namespace App\Http\Controllers;

use App\Models\GuestFolio;
use App\Models\GuestFolioItem;
use App\Models\GuestReservation;
use App\Models\GuestCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuestFolioController extends Controller
{
    public function index(Request $request)
    {
        $folios = GuestFolio::with(['guest', 'room', 'checkin.guest', 'checkin.room', 'checkin.reservation'])->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($folios);
        }

        return view('folios.index', compact('folios'));
    }

    public function show(Request $request, $folio_code)
    {
        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();
        $folio->load(['guest', 'checkin.guest', 'checkin.room']);
        $checkin = $folio->checkin;
        $items = $folio->items()->orderBy('posted_at')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'folio' => $folio,
                'checkin' => $checkin,
                'items' => $items,
            ]);
        }

        return view('folios.show', compact('checkin', 'folio', 'items'));
    }

    public function print(Request $request, $folio_code)
    {
        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();
        $folio->load(['guest', 'checkin.guest', 'checkin.room']);
        $checkin = $folio->checkin;
        $items = $folio->items()->orderBy('posted_at')->get();

        // You can return a print view or PDF here
        return view('folios.print', compact('checkin', 'folio', 'items'));
    }

    public function storeItem(Request $request, $folio_code)
    {
        $request->validate([
            'type' => 'required|in:charge,payment',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();

        $item = $folio->items()->create([
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'posted_at' => now(),
        ]);

        $folio->recalculateTotals();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Folio item added.',
                'item' => $item,
                'folio' => $folio->fresh(), // return updated folio
            ], 201);
        }

        return back()->with('success', 'Folio item added.');
    }

    public function destroyItem(Request $request, $id)
    {
        $item = GuestFolioItem::findOrFail($id);
        $folio = $item->folio;
        $item->delete();

        $folio->recalculateTotals();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Folio item deleted.',
                'folio' => $folio->fresh(),
            ]);
        }

        return back()->with('success', 'Folio item deleted.');
    }

    public function destroy(Request $request, $folio_code)
    {
        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();
        $folio->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Folio deleted.'
            ]);
        }

        return redirect()->route('folios.index')->with('success', 'Folio deleted.');
    }

    public function createForCheckin(Request $request, $checkin_code)
    {
        $checkin = GuestCheckin::with('room.roomType')->where('checkin_code', $checkin_code)->firstOrFail();

        do {
            $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $folioCode = 'FL-' . date('Ymd') . '-' . $random;
        } while (GuestFolio::where('folio_code', $folioCode)->exists());

        $folio = GuestFolio::firstOrCreate(
            ['checkin_code' => $checkin->checkin_code],
            [
                'folio_code' => $folioCode,
                'guest_code' => $checkin->guest_code,
                'room_code' => $checkin->room_code,
                'total_amount' => 0,
                'paid_amount' => 0,
                'status' => 'open',
                'currency' => 'USD'
            ]
        );

        $pricePerNight = $checkin->room->roomType->price_per_night ?? 0;
        $start = \Carbon\Carbon::parse($checkin->checkin_date);
        $end = \Carbon\Carbon::parse($checkin->checkout_date);
        $numberOfNights = $start->diffInDays($end);

        $roomCharge = $pricePerNight * $numberOfNights;

        $alreadyHasRoomCharge = $folio->items()->where('description', 'Room Charge')->exists();

        if ($roomCharge > 0 && !$alreadyHasRoomCharge) {
            $folio->items()->create([
                'type' => 'charge',
                'description' => 'Room Charge',
                'amount' => $roomCharge,
                'posted_at' => now(),
            ]);
            $folio->recalculateTotals();
        }

        if ($request->wantsJson()) {
            // Return the new folio and items as JSON
            return response()->json([
                'message' => 'Folio created for check-in.',
                'folio' => $folio->load(['items', 'guest', 'checkin']),
            ], 201);
        }

        return redirect()->route('folios.show', $folio->folio_code);
    }
}
