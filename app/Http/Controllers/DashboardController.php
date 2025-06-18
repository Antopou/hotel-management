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
use App\Models\GuestFolioItem;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Parse filter values
        $period = $request->input('period', 'all_time'); // Default to 'all_time'
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $queryStartDate = null;
        $queryEndDate = null;

        // Determine the date range based on the 'period' filter
        switch ($period) {
            case 'last_7_days':
                $queryStartDate = Carbon::now()->subDays(6)->startOfDay(); // Last 7 days including today
                $queryEndDate = Carbon::now()->endOfDay();
                break;
            case 'this_week':
                $queryStartDate = Carbon::now()->startOfWeek(); // Start of current week
                $queryEndDate = Carbon::now()->endOfWeek();
                break;
            case 'last_30_days':
                $queryStartDate = Carbon::now()->subDays(29)->startOfDay(); // Last 30 days including today
                $queryEndDate = Carbon::now()->endOfDay();
                break;
            case 'this_month':
                $queryStartDate = Carbon::now()->startOfMonth();
                $queryEndDate = Carbon::now()->endOfMonth();
                break;
            case 'last_3_months':
                $queryStartDate = Carbon::now()->subMonths(2)->startOfMonth(); // Current month and 2 months before
                $queryEndDate = Carbon::now()->endOfMonth();
                break;
            case 'last_6_months':
                $queryStartDate = Carbon::now()->subMonths(5)->startOfMonth(); // Current month and 5 months before
                $queryEndDate = Carbon::now()->endOfMonth();
                break;
            case 'this_year':
                $queryStartDate = Carbon::now()->startOfYear();
                $queryEndDate = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $queryStartDate = Carbon::now()->subYear()->startOfYear();
                $queryEndDate = Carbon::now()->subYear()->endOfYear();
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $queryStartDate = Carbon::parse($startDate)->startOfDay();
                    $queryEndDate = Carbon::parse($endDate)->endOfDay();
                }
                break;
            case 'all_time':
            default:
                // If 'all_time', $queryStartDate and $queryEndDate remain null, meaning no date filter will be applied
                break;
        }

        // Ensure a valid range for chart generation if period is not 'all_time' or 'custom'
        // For 'all_time', we'll get all data. For charts, we'll dynamically determine the range.
        // The charts will typically show data for a recent period (e.g., last 6 months)
        // even if "All Time" is selected for overall totals, to make the chart readable.
        // If queryStartDate and queryEndDate are set by the filter, use them for charts.
        // Otherwise, default to a sensible range for chart display (e.g., last 6 months).
        $chartFromDate = $queryStartDate ?: Carbon::now()->subMonths(5)->startOfMonth();
        $chartToDate = $queryEndDate ?: Carbon::now()->endOfMonth();

        // Ensure chart range doesn't go into the future
        if ($chartToDate->isFuture()) {
            $chartToDate = Carbon::now()->endOfDay();
        }
        if ($chartFromDate > $chartToDate) {
            $chartFromDate = $chartToDate->copy()->subMonths(5)->startOfMonth();
        }


        // 2. Get all months in the range for labels (for charts)
        $months = [];
        $currentChartMonth = $chartFromDate->copy();
        while ($currentChartMonth <= $chartToDate) {
            $months[] = $currentChartMonth->format('M Y');
            $currentChartMonth->addMonth();
        }

        // 3. Pull checkins in the period based on the calculated range
        $checkinsQuery = GuestCheckin::with(['folio.items']);

        if ($queryStartDate && $queryEndDate) {
            $checkinsQuery->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
        }
        $checkins = $checkinsQuery->get();

        // 4. Aggregate by month for charts (Revenue, Occupancy, ADR, RevPAR)
        $monthlyData = [];
        $roomCount = Room::count();

        // Initialize monthly data arrays with zeros for all months in the chart range
        foreach ($months as $monthLabel) {
            $monthKey = Carbon::createFromFormat('M Y', $monthLabel)->format('Y-m');
            $monthlyData[$monthKey] = [
                'total_revenue' => 0,
                'total_room_nights_sold' => 0,
                'total_checked_in_rooms' => 0 // To count unique rooms checked in for occupancy calculation
            ];
        }

        foreach ($checkins as $checkin) {
            $monthKey = Carbon::parse($checkin->checkin_date)->format('Y-m');

            // Ensure the month key exists in our chart range
            if (isset($monthlyData[$monthKey])) {
                $checkinDate = Carbon::parse($checkin->checkin_date);
                $checkoutDate = Carbon::parse($checkin->checkout_date);

                // Calculate nights, considering if reservation spans multiple months
                $nights = $checkoutDate->diffInDays($checkinDate);
                if ($nights <= 0) $nights = 1; // At least one night

                $roomRevenue = 0;
                if ($checkin->folio) {
                    foreach ($checkin->folio->items as $item) {
                        if ($item->type === 'charge') {
                            $roomRevenue += $item->amount;
                        }
                    }
                }

                $monthlyData[$monthKey]['total_revenue'] += $roomRevenue;
                $monthlyData[$monthKey]['total_room_nights_sold'] += $nights;
            }
        }

        $revenues = [];
        $occupancies = [];
        $adrs = [];
        $revpars = [];

        foreach ($months as $monthLabel) {
            $monthKey = Carbon::createFromFormat('M Y', $monthLabel)->format('Y-m');
            $data = $monthlyData[$monthKey] ?? [
                'total_revenue' => 0,
                'total_room_nights_sold' => 0
            ];

            $revenues[] = round($data['total_revenue'], 2);

            // Occupancy (based on nights sold / available nights in that specific month)
            $monthCarbon = Carbon::createFromFormat('M Y', $monthLabel);
            $daysInThisMonth = $monthCarbon->daysInMonth;
            $totalAvailableRoomNights = $roomCount * $daysInThisMonth;

            if ($totalAvailableRoomNights > 0) {
                $occupancies[] = round(($data['total_room_nights_sold'] / $totalAvailableRoomNights) * 100, 2);
            } else {
                $occupancies[] = 0;
            }

            // ADR (Average Daily Rate)
            if ($data['total_room_nights_sold'] > 0) {
                $adrs[] = round($data['total_revenue'] / $data['total_room_nights_sold'], 2);
            } else {
                $adrs[] = 0;
            }

            // RevPAR (Revenue Per Available Room)
            if ($totalAvailableRoomNights > 0) { // Use total available nights, not just room count
                $revpars[] = round($data['total_revenue'] / $totalAvailableRoomNights, 2);
            } else {
                $revpars[] = 0;
            }
        }

        // Global Dashboard Totals (apply filter if not 'all_time')
        $totalGuestsQuery = Guest::query();
        $totalReservationsQuery = GuestReservation::query();
        $totalCheckinsQuery = GuestCheckin::query();
        $totalFoliosQuery = GuestFolio::query();

        if ($queryStartDate && $queryEndDate) {
            // Guests are usually counted by their creation date, or by associated checkin/reservation date.
            // For dashboard purposes, we'll count guests who had a reservation or check-in within the period.
            // Using 'created_at' for GuestReservation as 'reservation_date' was not found.
            $totalGuestsQuery->whereHas('reservations', function($q) use ($queryStartDate, $queryEndDate) {
                $q->whereBetween('created_at', [$queryStartDate, $queryEndDate]); // Changed from reservation_date
            })->orWhereHas('checkins', function($q) use ($queryStartDate, $queryEndDate) {
                $q->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
            });

            $totalReservationsQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]); // Changed from reservation_date
            $totalCheckinsQuery->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
            $totalFoliosQuery->whereHas('checkin', function($q) use ($queryStartDate, $queryEndDate) {
                $q->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
            });
        }

        $totalGuests = $totalGuestsQuery->count();
        $totalReservations = $totalReservationsQuery->count();
        $totalRooms = Room::count(); // This is a static number, not filterable by time.
        $totalCheckins = $totalCheckinsQuery->count();
        $totalFolios = $totalFoliosQuery->count();

        // Total Revenue Calculation (already correctly filtered)
        $totalChargesQuery = GuestFolioItem::where('type', 'charge');
        $totalPaymentsQuery = GuestFolioItem::where('type', 'payment');

        if ($queryStartDate && $queryEndDate) {
            $totalChargesQuery->whereHas('folio.checkin', function($q) use ($queryStartDate, $queryEndDate) {
                $q->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
            });
            $totalPaymentsQuery->whereHas('folio.checkin', function($q) use ($queryStartDate, $queryEndDate) {
                $q->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
            });
        }

        $totalCharges = $totalChargesQuery->sum('amount');
        $totalPayments = $totalPaymentsQuery->sum('amount');
        $totalRevenue = $totalCharges - $totalPayments;


        // Latest reservations and check-ins: APPLY THE PERIOD FILTER
        $latestReservationsQuery = GuestReservation::with(['guest', 'room']);
        if ($queryStartDate && $queryEndDate) {
            $latestReservationsQuery->whereBetween('created_at', [$queryStartDate, $queryEndDate]); // Changed from reservation_date
        }
        $latestReservations = $latestReservationsQuery->orderByDesc('created_at')->take(5)->get(); // Changed from reservation_date

        $latestCheckinsQuery = GuestCheckin::with(['guest', 'room']);
        if ($queryStartDate && $queryEndDate) {
            $latestCheckinsQuery->whereBetween('checkin_date', [$queryStartDate, $queryEndDate]);
        }
        $latestCheckins = $latestCheckinsQuery->orderByDesc('checkin_date')->take(5)->get();


        // Room status counts
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();


        return view('dashboard', compact(
            'totalGuests', 'totalReservations', 'totalRooms',
            'totalCheckins', 'totalFolios', 'totalRevenue',
            'latestReservations', 'latestCheckins',
            'months', 'revenues', 'occupancies', 'adrs', 'revpars',
            'occupiedRooms', 'availableRooms', 'maintenanceRooms'
        ));
    }
}