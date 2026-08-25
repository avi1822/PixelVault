<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MembershipPlan;
use App\Models\Membership;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Yajra\Datatables\Datatables;

class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get active membership plans catalogue.
     */
    public function getPlans()
    {
        $plans = MembershipPlan::where('status', 'ACTIVE')->get();
        return response()->json($plans);
    }

    /**
     * Purchase / Subscribe to a Membership Plan.
     */
    public function purchasePlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:membership_plans,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $plan = MembershipPlan::where('id', $request->plan_id)->first();

            if ($plan->status !== 'ACTIVE') {
                return response()->json(['success' => false, 'message' => 'Selected membership plan is currently inactive.'], 422);
            }

            // Check if user already has an ACTIVE non-expired membership
            $currentMembership = Membership::where('user_id', $user->id)
                ->where('status', 'ACTIVE')
                ->where('expires_at', '>=', now())
                ->first();

            if ($currentMembership) {
                // Expire existing active membership before creating upgraded plan
                $currentMembership->update(['status' => 'EXPIRED']);
            }

            // Generate Membership Purchase Invoice
            $invNum = 'INV-MEM-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (Invoice::where('invoice_number', $invNum)->exists()) {
                $invNum = 'INV-MEM-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $invoice = Invoice::create([
                'invoice_number' => $invNum,
                'user_id' => $user->id,
                'subtotal' => $plan->price,
                'discount' => 0,
                'tax' => 0,
                'total' => $plan->price,
                'paid_amount' => 0,
                'status' => 'ISSUED',
                'issued_at' => now(),
                'notes' => 'Purchase of ' . $plan->name . ' Membership Plan'
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'MEMBERSHIP',
                'description' => $plan->name . ' Membership Plan (' . $plan->duration_days . ' Days Pass)',
                'quantity' => 1,
                'unit_price' => $plan->price,
                'amount' => $plan->price,
                'reference_type' => 'MembershipPlan',
                'reference_id' => $plan->id
            ]);

            // Create Membership record (will activate automatically once invoice is paid)
            $now = now();
            $expiresAt = (clone $now)->addDays($plan->duration_days);

            $membership = Membership::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'started_at' => $now,
                'expires_at' => $expiresAt,
                'status' => 'PENDING',
                'gaming_hours_allocated' => $plan->gaming_hours,
                'gaming_minutes_remaining' => $plan->gaming_hours * 60,
                'discount_percent' => $plan->gaming_discount_percent,
                'purchase_price' => $plan->price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Membership ' . $plan->name . ' invoice generated! Please complete payment to finalize activation.',
                'invoice' => $invoice,
                'membership' => $membership
            ]);
        });
    }

    /**
     * Get logged-in Customer's Active & Historical Memberships.
     */
    public function myMembership()
    {
        $userId = Auth::id();

        // Auto-expire outdated memberships
        Membership::where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->where('expires_at', '<', now())
            ->update(['status' => 'EXPIRED']);

        $active = Membership::with('plan')
            ->where('user_id', $userId)
            ->where('status', 'ACTIVE')
            ->where('expires_at', '>=', now())
            ->first();

        $history = Membership::with('plan')
            ->where('user_id', $userId)
            ->orderBy('started_at', 'desc')
            ->get();

        return response()->json([
            'active' => $active,
            'history' => $history
        ]);
    }

    /**
     * Yajra DataTables query for Admin Memberships management.
     */
    public function anyData()
    {
        // Auto-expire all outdated memberships on query
        Membership::where('status', 'ACTIVE')
            ->where('expires_at', '<', now())
            ->update(['status' => 'EXPIRED']);

        $memberships = Membership::with(['user', 'plan'])->select('memberships.*');

        return Datatables::of($memberships)
            ->addColumn('customer_name', function ($mem) {
                return $mem->user ? ($mem->user->first_name . ' ' . $mem->user->last_name) : 'N/A';
            })
            ->addColumn('plan_name', function ($mem) {
                return $mem->plan ? $mem->plan->name : 'Custom Plan';
            })
            ->editColumn('gaming_minutes_remaining', function ($mem) {
                $hours = floor($mem->gaming_minutes_remaining / 60);
                $mins = $mem->gaming_minutes_remaining % 60;
                return $hours . 'h ' . $mins . 'm';
            })
            ->editColumn('started_at', function ($mem) {
                return date('Y-m-d', strtotime($mem->started_at));
            })
            ->editColumn('expires_at', function ($mem) {
                return date('Y-m-d', strtotime($mem->expires_at));
            })
            ->make(true);
    }

    /**
     * Admin Store / Update Membership Plan.
     */
    public function storePlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'gaming_hours' => 'required|integer|min:0',
            'gaming_discount_percent' => 'required|integer|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->has('plan_id') && !empty($request->plan_id)) {
            $plan = MembershipPlan::find($request->plan_id);
            if ($plan) {
                $plan->update($request->only(['name', 'description', 'price', 'duration_days', 'gaming_hours', 'gaming_discount_percent', 'status']));
                return response()->json(['success' => true, 'message' => 'Membership Plan updated successfully!']);
            }
        }

        MembershipPlan::create([
            'name' => strtoupper($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'gaming_hours' => $request->gaming_hours,
            'gaming_discount_percent' => $request->gaming_discount_percent,
            'status' => 'ACTIVE'
        ]);

        return response()->json(['success' => true, 'message' => 'New Membership Plan created!']);
    }
}
