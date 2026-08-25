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
        $startDate = $request->query('start_date', date('Y-m-d'));
        $endDate = $request->query('end_date', date('Y-m-d'));

        return response()->json([
            'summary' => $this->analyticsService->getDashboardSummary($startDate, $endDate),
            'revenue_breakdown' => $this->analyticsService->getRevenueBreakdown($startDate, $endDate),
            'payment_methods' => $this->analyticsService->getPaymentAnalytics($startDate, $endDate),
            'station_utilization' => $this->analyticsService->getStationUtilization($startDate, $endDate),
            'memberships' => $this->analyticsService->getMembershipSummary(),
            'fnb_sales' => $this->analyticsService->getFnbSalesSummary($startDate, $endDate),
            'alerts' => $this->analyticsService->getOperationalAlerts()
        ]);
    }
}
