<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\GuestReservation;
use App\Models\Room;
use App\Models\GuestCheckin;
use App\Models\GuestFolio;


class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalGuests = Guest::count();
        $totalReservations = GuestReservation::count();
        $totalRooms = Room::count();
        $totalCheckins = GuestCheckin::count();
        $totalFolios = GuestFolio::count();
        $totalRevenue = GuestFolio::sum('total_amount'); 

        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->format('M Y');
            $months[] = $label;

            $revenue = GuestFolio::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->sum('total_amount');
            $revenues[] = $revenue;
        }


        // Recent Activity
        $latestReservations = GuestReservation::latest()->with(['guest', 'room'])->take(5)->get();
        $latestCheckins = GuestCheckin::latest()->with(['guest', 'room'])->take(5)->get();

        return view('dashboard', compact(
            'totalGuests', 'totalReservations', 'totalRooms',
            'totalCheckins', 'totalFolios', 'totalRevenue',
            'latestReservations', 'latestCheckins', 'months', 'revenues'
        ));
    }
}
