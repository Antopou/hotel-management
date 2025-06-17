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

        // Dynamically extract floor numbers from room names (e.g., "Room 102" => 1)
        $allRooms = Room::all();
        $floors = $allRooms->map(function($room) {
            // Extract the first digit from the room number in the name
            if (preg_match('/Room\s*(\d+)/i', $room->name, $matches)) {
                return intval(substr($matches[1], 0, 1));
            }
            return null;
        })->filter()->unique()->sort()->values();

        // Filter by floor using the first digit of the room number in the name
        if ($request->filled('floor') && $request->floor !== 'all') {
            $query->whereRaw("CAST(SUBSTRING(name, LOCATE(' ', name) + 1, 1) AS UNSIGNED) = ?", [$request->floor]);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $rooms = $query->paginate(12)->appends($request->except('page'));
        $guests = Guest::orderBy('name')->get();

        return view('front-desk.rooms', compact('rooms', 'guests', 'floors'));
    }
}