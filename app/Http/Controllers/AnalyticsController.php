<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->middleware(['auth', 'isAdmin']);
        $this->analyticsService = $analyticsService;
    }

    /**
     * Complete Executive Dashboard Analytics JSON payload.
     */
    public function dashboard(Request $request)
    {
        try {
            $startDate = $request->query('start_date', date('Y-m-d'));
            $endDate = $request->query('end_date', date('Y-m-d'));

            return response()->json([
                'success' => true,
                'summary' => $this->analyticsService->getDashboardSummary($startDate, $endDate),
                'revenue_breakdown' => $this->analyticsService->getRevenueBreakdown($startDate, $endDate),
                'payment_methods' => $this->analyticsService->getPaymentAnalytics($startDate, $endDate),
                'station_utilization' => $this->analyticsService->getStationUtilization($startDate, $endDate),
                'memberships' => $this->analyticsService->getMembershipSummary(),
                'fnb_sales' => $this->analyticsService->getFnbSalesSummary($startDate, $endDate),
                'alerts' => $this->analyticsService->getOperationalAlerts()
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Analytics Dashboard Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard metrics: ' . $e->getMessage(),
                'summary' => [
                    'date_range' => ['start' => date('Y-m-d'), 'end' => date('Y-m-d')],
                    'total_invoiced' => 0, 'total_paid' => 0, 'total_pending' => 0,
                    'total_invoices_count' => 0, 'active_sessions_count' => 0,
                    'today_visitors_count' => 0, 'today_reservations_count' => 0
                ],
                'revenue_breakdown' => ['gaming' => 0, 'food' => 0, 'membership' => 0, 'other' => 0, 'total' => 0],
                'payment_methods' => [
                    'cash' => ['collected' => 0, 'count' => 0],
                    'upi' => ['collected' => 0, 'count' => 0],
                    'card' => ['collected' => 0, 'count' => 0]
                ],
                'station_utilization' => [],
                'memberships' => ['active_memberships_count' => 0, 'plan_breakdown' => ['SILVER' => 0, 'GOLD' => 0, 'PLATINUM' => 0], 'total_membership_revenue' => 0, 'expiring_soon_count' => 0],
                'fnb_sales' => ['total_fnb_revenue' => 0, 'total_items_sold' => 0, 'top_products' => []],
                'alerts' => ['low_stock_products' => [], 'maintenance_stations' => [], 'pending_invoices_count' => 0, 'expiring_memberships_count' => 0]
            ], 200);
        }
    }
}
