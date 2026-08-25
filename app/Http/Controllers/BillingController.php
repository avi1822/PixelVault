<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\GamingSession;
use Yajra\Datatables\Datatables;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate Invoice for a completed or active gaming session.
     */
    public static function createInvoiceForSession(GamingSession $session)
    {
        return DB::transaction(function () use ($session) {
            // Check if invoice already exists
            $existing = Invoice::where('gaming_session_id', $session->id)->first();
            if ($existing) {
                return $existing;
            }

            // Generate unique invoice number
            $invNum = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (Invoice::where('invoice_number', $invNum)->exists()) {
                $invNum = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Historical price snapshot: use session base_amount
            $subtotal = (int) $session->base_amount;
            $discount = 0;
            $tax = 0;

            // Check if user has an ACTIVE membership for discount & hours deduction
            if ($session->user_id) {
                $membership = \App\Models\Membership::where('user_id', $session->user_id)
                    ->where('status', 'ACTIVE')
                    ->where('expires_at', '>=', now())
                    ->lockForUpdate()
                    ->first();

                if ($membership) {
                    // Apply membership percentage discount
                    if ($membership->discount_percent > 0) {
                        $discount = (int) round(($subtotal * $membership->discount_percent) / 100);
                    }

                    // Option A: Deduct actual session duration minutes from membership balance safely
                    $sessionMinutes = (int) ($session->duration_minutes ?? 60);
                    if ($membership->gaming_minutes_remaining > 0) {
                        $minutesToDeduct = min($membership->gaming_minutes_remaining, $sessionMinutes);
                        $newRemaining = max(0, $membership->gaming_minutes_remaining - $minutesToDeduct);
                        $membership->update(['gaming_minutes_remaining' => $newRemaining]);
                    }
                }
            }

            $total = max(0, $subtotal - $discount + $tax);

            $invoice = Invoice::create([
                'invoice_number' => $invNum,
                'user_id' => $session->user_id,
                'reservation_id' => $session->reservation_id,
                'gaming_session_id' => $session->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => 0,
                'status' => 'ISSUED',
                'issued_at' => now(),
                'notes' => 'Gaming Session #' . $session->id . ' on Station #' . $session->station_id
            ]);

            // Add line item with historical unit_price snapshot
            $stName = ($session->station_id <= 5) ? 'PS5 Lounge #' . $session->station_id : 'PC Arena #' . $session->station_id;
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'GAMING',
                'description' => 'Gaming Session on ' . $stName . ' (' . max(1, round($session->duration_minutes / 60)) . ' hour pass)',
                'quantity' => 1,
                'unit_price' => $subtotal,
                'amount' => $subtotal,
                'reference_type' => 'GamingSession',
                'reference_id' => $session->id
            ]);

            return $invoice;
        });
    }

    /**
     * Record a payment against an invoice.
     */
    public function recordPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|integer|min:1',
            'method' => 'required|in:CASH,UPI,CARD',
            'transaction_reference' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $invoice = Invoice::where('id', $request->invoice_id)->lockForUpdate()->first();

            if ($invoice->status === 'PAID') {
                return response()->json(['success' => false, 'message' => 'Invoice ' . $invoice->invoice_number . ' is ALREADY FULLY PAID!'], 422);
            }
            if ($invoice->status === 'CANCELLED') {
                return response()->json(['success' => false, 'message' => 'Cannot pay a CANCELLED invoice.'], 422);
            }

            $paymentAmount = (int) $request->amount;
            $remaining = $invoice->total - $invoice->paid_amount;

            // Reject overpayments
            if ($paymentAmount > $remaining) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount (Rs.' . $paymentAmount . ') exceeds remaining invoice balance (Rs.' . $remaining . ')!'
                ], 422);
            }

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $paymentAmount,
                'method' => $request->method,
                'transaction_reference' => $request->transaction_reference,
                'status' => 'COMPLETED',
                'paid_at' => now(),
                'notes' => 'Recorded by Staff'
            ]);

            $newPaidTotal = $invoice->paid_amount + $paymentAmount;
            $newStatus = ($newPaidTotal >= $invoice->total) ? 'PAID' : 'PARTIALLY_PAID';

            $invoice->update([
                'paid_amount' => $newPaidTotal,
                'status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment of Rs.' . $paymentAmount . ' recorded successfully for ' . $invoice->invoice_number,
                'invoice' => $invoice
            ]);
        });
    }

    /**
     * View Invoice Details (API / Modal).
     */
    public function viewInvoice(Request $request, $id)
    {
        $invoice = Invoice::with(['user', 'gamingSession', 'items', 'payments'])->find($id);

        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice not found.'], 444);
        }

        // Authorization: customer can only view own invoice
        if (!Auth::user()->isadmin && $invoice->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to invoice.'], 403);
        }

        return response()->json(['success' => true, 'invoice' => $invoice]);
    }

    /**
     * Yajra DataTables query for Admin Invoices.
     */
    public function anyData()
    {
        $invoices = Invoice::with(['user', 'gamingSession'])->select('invoices.*');

        return Datatables::of($invoices)
            ->addColumn('customer_name', function ($inv) {
                if ($inv->user) {
                    return $inv->user->first_name . ' ' . $inv->user->last_name;
                }
                return 'Guest Customer';
            })
            ->editColumn('subtotal', function ($inv) {
                return 'Rs.' . $inv->subtotal;
            })
            ->editColumn('total', function ($inv) {
                return 'Rs.' . $inv->total;
            })
            ->editColumn('paid_amount', function ($inv) {
                return 'Rs.' . $inv->paid_amount;
            })
            ->editColumn('issued_at', function ($inv) {
                return date('Y-m-d H:i', strtotime($inv->issued_at));
            })
            ->make(true);
    }

    /**
     * Logged-in Customer's Invoices list.
     */
    public function userInvoices()
    {
        $invoices = Invoice::with(['items'])
            ->where('user_id', Auth::id())
            ->orderBy('issued_at', 'desc')
            ->get();

        return response()->json($invoices);
    }

    /**
     * Admin Billing Dashboard summary totals.
     */
    public function summaryStats()
    {
        $today = date('Y-m-d');
        $todayInvoices = Invoice::whereDate('issued_at', $today)->get();

        $todaySales = $todayInvoices->sum('total');
        $todayPaid = $todayInvoices->sum('paid_amount');
        $todayPending = $todaySales - $todayPaid;
        $totalInvoicesCount = Invoice::count();

        return response()->json([
            'todaySales' => $todaySales,
            'todayPaid' => $todayPaid,
            'todayPending' => max(0, $todayPending),
            'totalInvoicesCount' => $totalInvoicesCount
        ]);
    }
}
