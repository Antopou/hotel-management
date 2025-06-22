<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckin;
use App\Models\GuestFolioItem;
use App\Models\Room;
use App\Models\RoomType; // Add at the top
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        // Total Revenue (this month)
        $totalRevenue = GuestFolioItem::where('type', 'charge')
            ->whereMonth('posted_at', now()->month)
            ->whereYear('posted_at', now()->year)
            ->sum('amount');

        // Occupancy Rate (current month)
        $totalRooms = Room::count();
        $totalNights = now()->daysInMonth * $totalRooms;
        $occupiedNights = GuestCheckin::whereMonth('checkin_date', now()->month)
            ->whereYear('checkin_date', now()->year)
            ->get()
            ->sum(function($checkin) {
                $checkinDate = Carbon::parse($checkin->checkin_date);
                $checkoutDate = $checkin->checkout_date ? Carbon::parse($checkin->checkout_date) : now();
                return $checkinDate->diffInDays($checkoutDate);
            });
        $occupancyRate = $totalNights > 0 ? ($occupiedNights / $totalNights) * 100 : 0;

        // Total Bookings (this month)
        $totalBookings = GuestCheckin::whereMonth('checkin_date', now()->month)
            ->whereYear('checkin_date', now()->year)
            ->count();

        // Average ADR (Average Daily Rate, this month)
        $adr = $occupiedNights > 0 ? ($totalRevenue / $occupiedNights) : 0;

        return view('reports.index', [
            'totalRevenue' => $totalRevenue,
            'occupancyRate' => $occupancyRate,
            'totalBookings' => $totalBookings,
            'averageADR' => $adr,
        ]);
    }

    public function revenue(Request $request)
    {
        // 1. Get filter parameters
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $roomTypes = RoomType::all();

        // 2. Determine date range based on period
        $dateRange = $this->getDateRange($request, $period, $startDate, $endDate);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // --- Base Query to select relevant check-ins ---
        $relevantCheckinsQuery = GuestCheckin::query();

        if ($startDate && $endDate) {
            $relevantCheckinsQuery->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('checkin_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->whereBetween('checkout_date', [$startDate, $endDate])
                            ->orWhereNull('checkout_date');
                      })
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->whereDate('checkin_date', '<', $startDate)
                                ->whereDate('checkout_date', '>', $endDate);
                      })
                      ->orWhere(function ($query) use ($startDate) {
                          $query->whereDate('checkin_date', '<', $startDate)
                                ->whereNull('checkout_date');
                      });
            });
        }
        $relevantCheckinsQuery->whereNull('cancelled_date');

        // --- Room type filter ---
        if ($request->filled('room_type')) {
            $relevantCheckinsQuery->whereHas('room', function($q) use ($request) {
                $q->where('room_type_code', $request->input('room_type'));
            });
        }

        // --- Eager load relationships ---
        $relevantCheckinsQuery->with(['room.roomType', 'folio.items']);

        $checkinsForCalculation = $relevantCheckinsQuery->get();

        // Initialize metrics
        $totalRevenue = 0;
        $totalNightsOccupied = 0;
        $totalRoomsInHotel = Room::count();
        $revenueByDate = [];

        // Calculate revenue for each day of the stay for each relevant check-in
        foreach ($checkinsForCalculation as $checkin) {
            $checkinDate = Carbon::parse($checkin->checkin_date);
            $checkoutDate = $checkin->checkout_date ? Carbon::parse($checkin->checkout_date) : Carbon::now();

            // Use folio items with type 'charge' for revenue calculation
            $folio = $checkin->folio;
            $roomRevenue = 0;
            if ($folio) {
                $roomRevenue = $folio->items->where('type', 'charge')->sum('amount');
            }

            $fullNights = $checkinDate->diffInDays($checkoutDate);

            if ($fullNights <= 0) {
                if (
                    $roomRevenue > 0 &&
                    $startDate && $endDate && // <-- add this check
                    $checkinDate->between($startDate, $endDate)
                ) {
                    $formattedDate = $checkinDate->format('Y-m-d');
                    $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $roomRevenue;
                    $totalNightsOccupied++;
                }
                // Optionally, handle the "all time" case:
                if ($roomRevenue > 0 && (!$startDate || !$endDate)) {
                    $formattedDate = $checkinDate->format('Y-m-d');
                    $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $roomRevenue;
                    $totalNightsOccupied++;
                }
                continue;
            }

            $dailyRate = $roomRevenue / $fullNights;

            $periodStartForCheckin = ($startDate && $checkinDate->lessThan($startDate)) ? $startDate : $checkinDate;
            $periodEndForCheckin = ($endDate && $checkoutDate->greaterThan($endDate)) ? $endDate : $checkoutDate;

            if ($periodStartForCheckin->greaterThanOrEqualTo($periodEndForCheckin)) {
                continue;
            }

            $periodDates = CarbonPeriod::create($periodStartForCheckin, '1 day', $periodEndForCheckin->subDay());

            foreach ($periodDates as $date) {
                $formattedDate = $date->format('Y-m-d');
                $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $dailyRate;
                $totalNightsOccupied++;
            }
        }

        // Count rooms sold per date
        $roomsSoldByDate = [];
        foreach ($checkinsForCalculation as $checkin) {
            $checkinDate = Carbon::parse($checkin->checkin_date);
            $checkoutDate = $checkin->checkout_date ? Carbon::parse($checkin->checkout_date) : Carbon::now();

            $periodStartForCheckin = ($startDate && $checkinDate->lessThan($startDate)) ? $startDate : $checkinDate;
            $periodEndForCheckin = ($endDate && $checkoutDate->greaterThan($endDate)) ? $endDate : $checkoutDate;

            if ($periodStartForCheckin->greaterThanOrEqualTo($periodEndForCheckin)) {
                continue;
            }

            $periodDates = CarbonPeriod::create($periodStartForCheckin, '1 day', $periodEndForCheckin->subDay());

            foreach ($periodDates as $date) {
                $formattedDate = $date->format('Y-m-d');
                $roomsSoldByDate[$formattedDate] = ($roomsSoldByDate[$formattedDate] ?? 0) + 1;
            }
        }

        // --- Finalize Key Metrics ---
        // Sum up the total revenue from the daily aggregation
        $totalRevenue = array_sum($revenueByDate);

        // ADR (Average Daily Rate): Total Revenue / Total Number of Occupied Room Nights
        $averageDailyRate = ($totalNightsOccupied > 0)
                            ? $totalRevenue / $totalNightsOccupied
                            : 0;

        // RevPAR (Revenue Per Available Room): Total Revenue / Total Available Room Nights
        $totalDaysInPeriod = 0;
        if ($startDate && $endDate) {
            $totalDaysInPeriod = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end day
        } elseif ($period === 'all_time') {
            // For 'all_time', calculate based on actual data range
            if (!empty($revenueByDate)) {
                $firstDate = Carbon::parse(min(array_keys($revenueByDate)));
                $lastDate = Carbon::parse(max(array_keys($revenueByDate)));
                $totalDaysInPeriod = $firstDate->diffInDays($lastDate) + 1;
            }
        }

        $totalAvailableRoomNights = ($totalRoomsInHotel > 0 && $totalDaysInPeriod > 0)
                                    ? $totalRoomsInHotel * $totalDaysInPeriod
                                    : 0;

        $revPar = ($totalAvailableRoomNights > 0)
                  ? $totalRevenue / $totalAvailableRoomNights
                  : 0;

        // Calculate occupancy rate
        $occupancyRate = ($totalAvailableRoomNights > 0)
                        ? ($totalNightsOccupied / $totalAvailableRoomNights) * 100
                        : 0;

        // --- Prepare Data for Chart & Detailed Table ---
        $chartLabels = [];
        $chartData = [];
        $roomTypeLabels = [];
        $roomTypeData = [];
        $roomTypeRevenue = [];

        // For chart (trend)
        foreach ($revenueByDate as $date => $total) {
            $carbonDate = Carbon::parse($date);
            $chartLabels[] = $carbonDate->format('M d');
            $chartData[] = round($total, 2);
        }

        // For room type chart
        foreach ($checkinsForCalculation as $checkin) {
            $roomType = $checkin->room->roomType->name ?? 'Unknown';
            $folio = $checkin->folio;
            $roomRevenue = $folio ? $folio->items->where('type', 'charge')->sum('amount') : 0;
            $roomTypeRevenue[$roomType] = ($roomTypeRevenue[$roomType] ?? 0) + $roomRevenue;
        }
        foreach ($roomTypeRevenue as $type => $amount) {
            $roomTypeLabels[] = $type;
            $roomTypeData[] = round($amount, 2);
        }

        // For table (Revenue Details)
        $revenueDetails = [];
        foreach ($revenueByDate as $date => $total) {
            $carbonDate = Carbon::parse($date);
            $roomsSold = $roomsSoldByDate[$date] ?? 0;
            $occupancy = $totalRoomsInHotel > 0 ? ($roomsSold / $totalRoomsInHotel) * 100 : 0;

            $revenueDetails[] = (object)[
                'date' => $carbonDate->format('Y-m-d'),
                'room_type_name' => 'All Types', // You can group by room type if needed
                'rooms_sold' => $roomsSold,
                'average_rate' => $total, // Or calculate as needed
                'revenue' => $total,
                'occupancy_rate' => $occupancy,
            ];
        }

        // Pagination
        $page = request()->input('page', 1);
        $perPage = 15; // Or any number you prefer
        $offset = ($page - 1) * $perPage;
        $paginatedRevenueDetails = new LengthAwarePaginator(
            array_slice($revenueDetails, $offset, $perPage, true),
            count($revenueDetails),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Room Revenue for summary card
        $roomRevenue = array_sum($roomTypeData);

        // Export logic
        if ($request->get('export') === 'excel') {
            // Implement Excel export here (see below)
        }
        if ($request->get('export') === 'pdf') {
            // Implement PDF export here (see below)
        }

        return view('reports.revenue', compact(
            'totalRevenue',
            'roomRevenue',
            'averageDailyRate',
            'occupancyRate',
            'chartLabels',
            'chartData',
            'roomTypeLabels',
            'roomTypeData',
            'revenueDetails',
            'paginatedRevenueDetails',
            'roomTypes'
        ));
    }

    public function exportRevenue(Request $request)
    {
        // Repeat the same logic as in revenue() to get $revenueDetails
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dateRange = $this->getDateRange($request, $period, $startDate, $endDate);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // --- Base Query to select relevant check-ins ---
        $relevantCheckinsQuery = GuestCheckin::query();

        if ($startDate && $endDate) {
            $relevantCheckinsQuery->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('checkin_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->whereBetween('checkout_date', [$startDate, $endDate])
                            ->orWhereNull('checkout_date');
                      })
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->whereDate('checkin_date', '<', $startDate)
                                ->whereDate('checkout_date', '>', $endDate);
                      })
                      ->orWhere(function ($query) use ($startDate) {
                          $query->whereDate('checkin_date', '<', $startDate)
                                ->whereNull('checkout_date');
                      });
            });
        }
        $relevantCheckinsQuery->whereNull('cancelled_date');
        $checkinsForCalculation = $relevantCheckinsQuery->get();

        // Initialize metrics
        $totalRevenue = 0;
        $totalNightsOccupied = 0;
        $totalRoomsInHotel = Room::count();
        $revenueByDate = [];

        // Calculate revenue for each day of the stay for each relevant check-in
        foreach ($checkinsForCalculation as $checkin) {
            $checkinDate = Carbon::parse($checkin->checkin_date);
            $checkoutDate = $checkin->checkout_date ? Carbon::parse($checkin->checkout_date) : Carbon::now();

            // Use folio items with type 'charge' for revenue calculation
            $folio = $checkin->folio;
            $roomRevenue = 0;
            if ($folio) {
                $roomRevenue = $folio->items->where('type', 'charge')->sum('amount');
            }

            $fullNights = $checkinDate->diffInDays($checkoutDate);

            if ($fullNights <= 0) {
                if (
                    $roomRevenue > 0 &&
                    $startDate && $endDate && // <-- add this check
                    $checkinDate->between($startDate, $endDate)
                ) {
                    $formattedDate = $checkinDate->format('Y-m-d');
                    $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $roomRevenue;
                    $totalNightsOccupied++;
                }
                // Optionally, handle the "all time" case:
                if ($roomRevenue > 0 && (!$startDate || !$endDate)) {
                    $formattedDate = $checkinDate->format('Y-m-d');
                    $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $roomRevenue;
                    $totalNightsOccupied++;
                }
                continue;
            }

            $dailyRate = $roomRevenue / $fullNights;

            $periodStartForCheckin = ($startDate && $checkinDate->lessThan($startDate)) ? $startDate : $checkinDate;
            $periodEndForCheckin = ($endDate && $checkoutDate->greaterThan($endDate)) ? $endDate : $checkoutDate;

            if ($periodStartForCheckin->greaterThanOrEqualTo($periodEndForCheckin)) {
                continue;
            }

            $periodDates = CarbonPeriod::create($periodStartForCheckin, '1 day', $periodEndForCheckin->subDay());

            foreach ($periodDates as $date) {
                $formattedDate = $date->format('Y-m-d');
                $revenueByDate[$formattedDate] = ($revenueByDate[$formattedDate] ?? 0) + $dailyRate;
                $totalNightsOccupied++;
            }
        }

        // --- Prepare Data for CSV Export ---
        $revenueDetails = [];

        // Sort by date for proper chronological display
        ksort($revenueByDate);

        // Fill export data
        foreach ($revenueByDate as $date => $total) {
            $carbonDate = Carbon::parse($date);

            // For CSV export
            $revenueDetails[] = [
                'date_or_month' => $carbonDate->format('Y-m-d'),
                'room_revenue' => round($total, 2),
                'fb_revenue' => 0, // Placeholder for F&B - integrate from GuestFolio if available
                'other_revenue' => 0, // Placeholder for Other - integrate from GuestFolio if available
                'total_revenue' => round($total, 2), // This total is based on room revenue only here
            ];
        }

        // CSV Export
        if ($request->get('format') === 'csv') {
            $filename = 'revenue_report_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($revenueDetails) {
                $handle = fopen('php://output', 'w');
                // Header row
                fputcsv($handle, ['Date / Month', 'Room Revenue', 'F&B Revenue', 'Other Revenue', 'Total Revenue']);
                foreach ($revenueDetails as $detail) {
                    fputcsv($handle, [
                        $detail['date_or_month'],
                        $detail['room_revenue'],
                        $detail['fb_revenue'],
                        $detail['other_revenue'],
                        $detail['total_revenue'],
                    ]);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        // PDF Export
        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('reports.export_revenue_pdf', [
                'revenueDetails' => $revenueDetails,
            ]);
            return $pdf->download('revenue_report_' . now()->format('Ymd_His') . '.pdf');
        }

        // Default: redirect back or show error
        return back()->with('error', 'Invalid export format.');
    }

    /**
     * Helper function to determine date range based on period filter.
     */
    protected function getDateRange(Request $request, $period, $startDate = null, $endDate = null)
    {
        $start = null;
        $end = null;

        switch ($period) {
            case 'last_7_days':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'last_30_days':
                $start = Carbon::now()->subDays(29)->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'last_3_months':
                $start = Carbon::now()->subMonths(2)->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'last_6_months':
                $start = Carbon::now()->subMonths(5)->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear();
                $end = Carbon::now()->subYear()->endOfYear();
                break;
            case 'custom':
                // Custom dates will be handled by the direct checks below
                break;
            case 'all_time':
            default:
                // No date constraints, $start and $end remain null
                break;
        }

        if ($request->filled('start_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
        }
        if ($request->filled('end_date')) {
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
        }

        return ['start' => $start, 'end' => $end];
    }
}
