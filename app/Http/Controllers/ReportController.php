<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckin; // Use GuestCheckin model
use App\Models\Room; // Assuming this is your Room model
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        // 1. Get filter parameters
        $period = $request->input('period', 'all_time');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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
                if ($roomRevenue > 0 && $checkinDate->between($startDate, $endDate)) {
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
            // For 'all_time', RevPAR becomes less meaningful without a fixed period.
            // You might want to define a default period or calculate based on the first/last checkin/checkout dates
            // For now, setting it to 0 if no specific period is selected
            $totalDaysInPeriod = 0;
        }

        $totalAvailableRoomNights = ($totalRoomsInHotel > 0 && $totalDaysInPeriod > 0)
                                    ? $totalRoomsInHotel * $totalDaysInPeriod
                                    : 0;

        $revPar = ($totalAvailableRoomNights > 0)
                  ? $totalRevenue / $totalAvailableRoomNights
                  : 0;


        // --- Prepare Data for Chart & Detailed Table ---
        $revenueLabels = [];
        $revenueValues = [];
        $revenueDetails = [];

        // Sort by date for proper chronological display
        ksort($revenueByDate);

        // Fill chart and table data
        foreach ($revenueByDate as $date => $total) {
            $carbonDate = Carbon::parse($date);

            // For chart labels (e.g., 'Jan 01')
            $revenueLabels[] = $carbonDate->format('M d');
            $revenueValues[] = $total;

            // For detailed table
            $revenueDetails[] = [
                'date_or_month' => $carbonDate->format('Y-m-d'),
                'room_revenue' => $total,
                'fb_revenue' => 0, // Placeholder for F&B - integrate from GuestFolio if available
                'other_revenue' => 0, // Placeholder for Other - integrate from GuestFolio if available
                'total_revenue' => $total, // This total is based on room revenue only here
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

        // 4. Pass data to the view
        return view('reports.revenue', compact(
            'totalRevenue',
            'averageDailyRate',
            'revPar',
            'revenueLabels',
            'revenueValues',
            'paginatedRevenueDetails' // <-- use this in the view
        ));
    }

    public function exportRevenue(Request $request)
    {
        // Repeat the same logic as in revenue() to get $revenueDetails
        $period = $request->input('period', 'all_time');
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
                if ($roomRevenue > 0 && $checkinDate->between($startDate, $endDate)) {
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
            // For 'all_time', RevPAR becomes less meaningful without a fixed period.
            // You might want to define a default period or calculate based on the first/last checkin/checkout dates
            // For now, setting it to 0 if no specific period is selected
            $totalDaysInPeriod = 0;
        }

        $totalAvailableRoomNights = ($totalRoomsInHotel > 0 && $totalDaysInPeriod > 0)
                                    ? $totalRoomsInHotel * $totalDaysInPeriod
                                    : 0;

        $revPar = ($totalAvailableRoomNights > 0)
                  ? $totalRevenue / $totalAvailableRoomNights
                  : 0;


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
                'room_revenue' => $total,
                'fb_revenue' => 0, // Placeholder for F&B - integrate from GuestFolio if available
                'other_revenue' => 0, // Placeholder for Other - integrate from GuestFolio if available
                'total_revenue' => $total, // This total is based on room revenue only here
            ];
        }

        // CSV Export
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

    /**
     * Helper function to determine date range based on period filter.
     * (No changes needed here as it handles the $request object correctly)
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