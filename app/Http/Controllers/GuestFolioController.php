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
    public function index()
    {
        $folios = GuestFolio::with(['guest', 'room', 'checkin.guest', 'checkin.room', 'checkin.reservation'])->latest()->paginate(20);
        return view('folios.index', compact('folios'));
    }
    public function show($checkin_code)
    {
        $folio = GuestFolio::where('checkin_code', $checkin_code)->first();

        if (!$folio) {
            $checkin = GuestCheckin::with(['guest', 'room'])
                ->where('checkin_code', $checkin_code)->first();

            if ($checkin) {
                $folio = GuestFolio::firstOrCreate(
                    ['checkin_code' => $checkin->checkin_code],
                    [
                        'folio_code' => (string) Str::uuid(),
                        'guest_code' => $checkin->guest_code,
                        'room_code' => $checkin->room_code,
                        'total_amount' => 0,
                        'paid_amount' => 0,
                        'status' => 'open',
                        'currency' => 'USD',
                    ]
                );
            }
        }

        if (!$folio) {
            abort(404, 'Folio not found or could not be created');
        }

        $folio->load(['guest', 'checkin.guest', 'checkin.room']);
        $checkin = $folio->checkin; // already loaded
        $items = $folio->items()->orderBy('posted_at')->get();

        return view('folios.show', compact('checkin', 'folio', 'items'));
    }

    public function storeItem(Request $request, $folio_id)
    {
        $request->validate([
            'type' => 'required|in:charge,payment',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $folio = GuestFolio::findOrFail($folio_id);

        $folio->items()->create([
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'posted_at' => now(),
        ]);

        $folio->recalculateTotals();

        return back()->with('success', 'Folio item added.');
    }

    public function destroyItem($id)
    {
        $item = GuestFolioItem::findOrFail($id);
        $folio = $item->folio;
        $item->delete();

        $folio->recalculateTotals();

        return back()->with('success', 'Folio item deleted.');
    }

}
