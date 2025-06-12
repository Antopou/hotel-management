<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use Carbon\Carbon;

class FrontDeskController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['roomType', 'checkins' => function($query) {
            $query->where('is_checkout', false)
                  ->with('guest');
        }, 'reservations' => function($query) {
            $query->where('status', 'confirmed')
                  ->where('checkin_date', '>=', Carbon::now())
                  ->orderBy('checkin_date')
                  ->with('guest');
        }])->get();
        
        $guests = Guest::orderBy('name')->get();
        
        return view('frontdesk.rooms', compact('rooms', 'guests'));
    }
}