<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FrontDeskController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['roomType', 'checkins' => function($query) {
            $query->where('is_checkout', false)->with('guest');
        }, 'reservations' => function($query) {
            $query->where('status', 'confirmed')
                  ->where('checkin_date', '>=', Carbon::now())
                  ->orderBy('checkin_date')
                  ->with('guest');
        }]);

        // Filter by floor
        if ($request->filled('floor') && $request->floor !== 'all') {
            // Assuming your rooms have a 'floor' column. If not, adjust accordingly.
            $query->where('floor', $request->floor);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $rooms = $query->paginate(12)->appends($request->except('page'));
        $guests = Guest::orderBy('name')->get();

        return view('front-desk.rooms', compact('rooms', 'guests'));
    }
}