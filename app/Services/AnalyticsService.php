<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\GamingSession;
use App\Models\Reservation;
use App\Models\Computer;
use App\Models\VisitorEntry;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\InventoryProduct;
use App\Models\StockAdjustment;

class AnalyticsService
{
    /**
     * Get Executive Overview Summary.
     */
    public function getDashboardSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-d');
        $endDate = $endDate ?? date('Y-m-d');

        $invoicesQuery = Invoice::whereDate('issued_at', '>=', $startDate)
            ->whereDate('issued_at', '<=', $endDate);

        $totalInvoiced = (int) $invoicesQuery->sum('total');
        $totalPaid = (int) $invoicesQuery->sum('paid_amount');
        $totalPending = max(0, $totalInvoiced - $totalPaid);
        $totalInvoicesCount = $invoicesQuery->count();

        $activeSessionsCount = GamingSession::where('status', 'ACTIVE')->count();
        $todayVisitorsCount = VisitorEntry::whereDate('entry_date', '>=', $startDate)
            ->whereDate('entry_date', '<=', $endDate)
            ->count();
        $todayReservationsCount = Reservation::whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->count();

        return [
            'date_range' => ['start' => $startDate, 'end' => $endDate],
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'total_invoices_count' => $totalInvoicesCount,
            'active_sessions_count' => $activeSessionsCount,
            'today_visitors_count' => $todayVisitorsCount,
            'today_reservations_count' => $todayReservationsCount
        ];
    }

    /**
     * Get Revenue Breakdown by Item Category (GAMING, FOOD, MEMBERSHIP, OTHER).
     */
    public function getRevenueBreakdown($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-d');
        $endDate = $endDate ?? date('Y-m-d');

        $items = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->whereDate('invoices.issued_at', '>=', $startDate)
            ->whereDate('invoices.issued_at', '<=', $endDate)
            ->select('invoice_items.item_type', DB::raw('SUM(invoice_items.amount) as total_amount'))
            ->groupBy('invoice_items.item_type')
            ->pluck('total_amount', 'item_type')
            ->toArray();

        return [
            'gaming' => (int) ($items['GAMING'] ?? 0),
            'food' => (int) ($items['FOOD'] ?? 0),
            'membership' => (int) ($items['MEMBERSHIP'] ?? 0),
            'other' => (int) ($items['OTHER'] ?? 0),
            'total' => (int) array_sum($items)
        ];
    }

    /**
     * Get Payment Methods Analytics (CASH, UPI, CARD).
     */
    public function getPaymentAnalytics($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-d');
        $endDate = $endDate ?? date('Y-m-d');

        $payments = Payment::whereDate('paid_at', '>=', $startDate)
            ->whereDate('paid_at', '<=', $endDate)
            ->where('status', 'COMPLETED')
            ->select('method', DB::raw('SUM(amount) as total_collected'), DB::raw('COUNT(id) as transaction_count'))
            ->groupBy('method')
            ->get()
            ->keyBy('method');

        return [
            'cash' => [
                'collected' => (int) ($payments['CASH']->total_collected ?? 0),
                'count' => (int) ($payments['CASH']->transaction_count ?? 0)
            ],
            'upi' => [
                'collected' => (int) ($payments['UPI']->total_collected ?? 0),
                'count' => (int) ($payments['UPI']->transaction_count ?? 0)
            ],
            'card' => [
                'collected' => (int) ($payments['CARD']->total_collected ?? 0),
                'count' => (int) ($payments['CARD']->transaction_count ?? 0)
            ]
        ];
    }

    /**
     * Gaming Sessions Metrics & Station Utilization.
     */
    public function getStationUtilization($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-d');
        $endDate = $endDate ?? date('Y-m-d');

        // Total operational hours calculation assumption: 12 hours/day operating time per station
        $daysCount = max(1, (strtotime($endDate) - strtotime($startDate)) / 86400 + 1);
        $totalOperatingMinutesPerStation = $daysCount * 12 * 60; // 720 mins per day per station

        $stations = Computer::all();
        $sessionStats = DB::table('gaming_sessions')
            ->whereDate('started_at', '>=', $startDate)
            ->whereDate('started_at', '<=', $endDate)
            ->select('station_id', DB::raw('COUNT(id) as total_sessions'), DB::raw('SUM(COALESCE(duration_minutes, 60)) as total_minutes'))
            ->groupBy('station_id')
            ->pluck('total_minutes', 'station_id')
            ->toArray();

        $sessionCounts = DB::table('gaming_sessions')
            ->whereDate('started_at', '>=', $startDate)
            ->whereDate('started_at', '<=', $endDate)
            ->select('station_id', DB::raw('COUNT(id) as total_sessions'))
            ->groupBy('station_id')
            ->pluck('total_sessions', 'station_id')
            ->toArray();

        $result = [];
        foreach ($stations as $st) {
            $playedMins = (int) ($sessionStats[$st->cid] ?? 0);
            $sessCount = (int) ($sessionCounts[$st->cid] ?? 0);
            $utilizationPct = min(100.0, round(($playedMins / $totalOperatingMinutesPerStation) * 100, 1));
            $label = ($st->cid <= 5) ? 'PS5 Lounge #' . $st->cid : 'PC Arena #' . $st->cid;

            $result[] = [
                'station_id' => $st->cid,
                'station_label' => $label,
                'status' => $st->status ?? 'AVAILABLE',
                'session_count' => $sessCount,
                'gaming_minutes' => $playedMins,
                'utilization_percent' => $utilizationPct
            ];
        }

        return $result;
    }

    /**
     * Membership Performance Summary.
     */
    public function getMembershipSummary()
    {
        $activeMemberships = Membership::where('status', 'ACTIVE')
            ->where('expires_at', '>=', now())
            ->get();

        $planCounts = [
            'SILVER' => 0,
            'GOLD' => 0,
            'PLATINUM' => 0
        ];

        foreach ($activeMemberships as $m) {
            if ($m->plan) {
                $planCounts[strtoupper($m->plan->name)] = ($planCounts[strtoupper($m->plan->name)] ?? 0) + 1;
            }
        }

        $totalRevenue = (int) DB::table('invoice_items')
            ->where('item_type', 'MEMBERSHIP')
            ->sum('amount');

        $expiringSoonCount = Membership::where('status', 'ACTIVE')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();

        return [
            'active_memberships_count' => $activeMemberships->count(),
            'plan_breakdown' => $planCounts,
            'total_membership_revenue' => $totalRevenue,
            'expiring_soon_count' => $expiringSoonCount
        ];
    }

    /**
     * F&B Products Sales Analytics (using historical snapshot amounts).
     */
    public function getFnbSalesSummary($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? date('Y-m-d');
        $endDate = $endDate ?? date('Y-m-d');

        $sales = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoice_items.item_type', 'FOOD')
            ->whereDate('invoices.issued_at', '>=', $startDate)
            ->whereDate('invoices.issued_at', '<=', $endDate)
            ->select(
                'invoice_items.description',
                DB::raw('SUM(invoice_items.quantity) as total_qty'),
                DB::raw('SUM(invoice_items.amount) as total_revenue')
            )
            ->groupBy('invoice_items.description')
            ->orderBy('total_revenue', 'desc')
            ->get();

        $totalFnbRevenue = (int) $sales->sum('total_revenue');
        $totalItemsSold = (int) $sales->sum('total_qty');

        return [
            'total_fnb_revenue' => $totalFnbRevenue,
            'total_items_sold' => $totalItemsSold,
            'top_products' => $sales
        ];
    }

    /**
     * Inventory & Operational Health Alerts.
     */
    public function getOperationalAlerts()
    {
        $lowStockProducts = InventoryProduct::where('status', '!=', 'INACTIVE')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->get(['id', 'name', 'stock_quantity', 'reorder_level']);

        $maintenanceStations = Computer::whereIn('status', ['MAINTENANCE', 'OFFLINE'])->get(['cid', 'status']);
        $pendingInvoicesCount = Invoice::where('status', 'ISSUED')->count();
        $expiringMembershipsCount = Membership::where('status', 'ACTIVE')
            ->where('expires_at', '>=', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();

        return [
            'low_stock_products' => $lowStockProducts,
            'maintenance_stations' => $maintenanceStations,
            'pending_invoices_count' => $pendingInvoicesCount,
            'expiring_memberships_count' => $expiringMembershipsCount
        ];
    }
}
