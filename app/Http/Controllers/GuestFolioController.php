<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GuestFolio;
use App\Models\GuestFolioItem;
use App\Models\GuestReservation;
use App\Models\GuestCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestFolioController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestFolio::with(['guest', 'room', 'checkin.guest', 'checkin.room', 'checkin.reservation']);

        // Filtering
        if ($request->filled('guest')) {
            $query->whereHas('checkin.guest', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->guest . '%');
            });
        }
        if ($request->filled('folio_code')) {
            $query->where('folio_code', 'like', '%' . $request->folio_code . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $folios = $query->latest()->paginate(20);

        // Stats
        $totalRevenue = GuestFolio::sum('paid_amount');
        $pendingPayments = GuestFolio::sum(DB::raw('total_amount - paid_amount'));
        $avgFolioValue = GuestFolio::avg('total_amount');

        return view('folios.index', compact('folios', 'totalRevenue', 'pendingPayments', 'avgFolioValue'));
    }

    public function show($folio_code)
    {
        $folio = GuestFolio::with(['guest', 'checkin.guest', 'checkin.room'])->where('folio_code', $folio_code)->firstOrFail();
        $checkin = $folio->checkin;
        $items = $folio->items()->orderBy('posted_at')->get();

        return view('folios.show', compact('checkin', 'folio', 'items'));
    }

    public function print($folio_code)
    {
        $folio = GuestFolio::with(['guest', 'checkin.guest', 'checkin.room'])->where('folio_code', $folio_code)->firstOrFail();
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
            'reference' => 'nullable|string|max:255',
        ]);

        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();

        $folio->items()->create([
            'type' => $request->type,
            'description' => $request->description,
            'amount' => $request->amount,
            'reference' => $request->reference,
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

    public function destroy($folio_code)
    {
        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();
        $folio->delete();

        return redirect()->route('folios.index')->with('success', 'Folio deleted.');
    }

    public function createForCheckin($checkin_code)
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

        return redirect()->route('folios.show', $folio->folio_code);
    }

    public function showFrontdesk($folio_code)
    {
        $folio = GuestFolio::with(['guest', 'checkin.guest', 'checkin.room'])->where('folio_code', $folio_code)->firstOrFail();
        $checkin = $folio->checkin;
        $items = $folio->items()->orderBy('posted_at')->get();

        // Use the new frontdesk/folios/show.blade.php
        return view('front-desk.folios.show', compact('checkin', 'folio', 'items'));
    }

    public function export(Request $request)
    {
        $query = GuestFolio::with(['guest', 'room']);

        // Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $folios = $query->get();

        $format = $request->input('format', 'csv');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="folios.csv"',
            ];

            $callback = function() use ($folios, $request) {
                $handle = fopen('php://output', 'w');
                $columns = ['Folio Code', 'Guest', 'Room', 'Total', 'Paid', 'Balance', 'Status'];
                if ($request->input('include_items')) {
                    $columns[] = 'Items';
                }
                fputcsv($handle, $columns);

                foreach ($folios as $folio) {
                    $row = [
                        $folio->folio_code,
                        $folio->guest->name ?? '',
                        $folio->room->name ?? '',
                        $folio->total_amount,
                        $folio->paid_amount,
                        $folio->balance,
                        $folio->status,
                    ];
                    if ($request->input('include_items')) {
                        $items = $folio->items->map(function($item) {
                            return $item->type . ': ' . $item->description . ' (' . $item->amount . ')';
                        })->implode('; ');
                        $row[] = $items;
                    }
                    fputcsv($handle, $row);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($format === 'excel') {
            // For Excel export, you should use a package like Maatwebsite\Excel.
            // Here is a simple placeholder response:
            return back()->with('error', 'Excel export not implemented. Please use CSV.');
        }

        if ($format === 'pdf') {
            // Eager load items if needed
            if ($request->input('include_items')) {
                $folios->load('items');
            }
            $pdf = Pdf::loadView('folios.export_pdf', ['folios' => $folios]);
            return $pdf->download('folios.pdf');
        }

        return back()->with('error', 'Invalid export format.');
    }

    public function storePayment(Request $request, $folio_code)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $folio = GuestFolio::where('folio_code', $folio_code)->firstOrFail();

        // Create payment as a folio item
        $folio->items()->create([
            'type' => 'payment',
            'description' => ucfirst($request->payment_method) . ' Payment',
            'amount' => $request->amount,
            'reference' => $request->reference_number,
            'posted_at' => now(),
            'notes' => $request->notes,
        ]);

        $folio->recalculateTotals();

        // Automatically set status to 'paid' if balance is zero or less
        if ($folio->balance <= 0) {
            $folio->status = 'paid';
            $folio->save();
        }

        return back()->with('success', 'Payment added successfully.');
    }
}
