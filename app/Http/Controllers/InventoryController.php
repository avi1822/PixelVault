<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\InventoryCategory;
use App\Models\InventoryProduct;
use App\Models\StockAdjustment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all available products (catalog for ordering).
     */
    public function getProducts()
    {
        $products = InventoryProduct::with('category')
            ->where('status', '!=', 'INACTIVE')
            ->get();

        return response()->json($products);
    }

    /**
     * Order F&B item & generate Invoice + automatically deduct stock atomically.
     */
    public function orderProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:inventory_products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $product = InventoryProduct::where('id', $request->product_id)->lockForUpdate()->first();
            $qty = (int) $request->quantity;

            if ($product->status === 'OUT_OF_STOCK' || $product->stock_quantity < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for ' . $product->name . '! Only ' . $product->stock_quantity . ' available.'
                ], 422);
            }

            // Deduct stock atomically
            $stockBefore = $product->stock_quantity;
            $stockAfter = $stockBefore - $qty;
            $newStatus = ($stockAfter <= 0) ? 'OUT_OF_STOCK' : 'AVAILABLE';

            $product->update([
                'stock_quantity' => $stockAfter,
                'status' => $newStatus
            ]);

            // Log Stock Adjustment
            StockAdjustment::create([
                'product_id' => $product->id,
                'type' => 'SALE',
                'quantity' => -$qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => 'Customer F&B Order',
                'user_id' => $user->id
            ]);

            // Calculate amount with historical snapshot
            $unitPrice = $product->selling_price;
            $subtotal = $unitPrice * $qty;

            // Generate F&B Invoice
            $invNum = 'INV-FB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (Invoice::where('invoice_number', $invNum)->exists()) {
                $invNum = 'INV-FB-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $invoice = Invoice::create([
                'invoice_number' => $invNum,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => 0,
                'total' => $subtotal,
                'paid_amount' => 0,
                'status' => 'ISSUED',
                'issued_at' => now(),
                'notes' => 'F&B Snack Order: ' . $product->name
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_type' => 'FOOD',
                'description' => $product->name,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'amount' => $subtotal,
                'reference_type' => 'InventoryProduct',
                'reference_id' => $product->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'F&B Order for ' . $product->name . ' (Qty: ' . $qty . ') placed! Invoice #' . $invNum . ' generated.',
                'invoice' => $invoice
            ]);
        });
    }

    /**
     * Yajra DataTables query for Admin Products & Stock.
     */
    public function anyData()
    {
        $products = InventoryProduct::with('category')->select('inventory_products.*');

        return DataTables::of($products)
            ->addColumn('category_name', function ($p) {
                return $p->category ? $p->category->name : 'General';
            })
            ->editColumn('cost_price', function ($p) {
                return 'Rs.' . $p->cost_price;
            })
            ->editColumn('selling_price', function ($p) {
                return 'Rs.' . $p->selling_price;
            })
            ->editColumn('stock_quantity', function ($p) {
                $alert = ($p->stock_quantity <= $p->reorder_level) ? ' ⚠️ Low Stock' : '';
                return $p->stock_quantity . $alert;
            })
            ->make(true);
    }

    /**
     * Admin Stock Adjustment (Add stock / manual correction).
     */
    public function adjustStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:inventory_products,id',
            'quantity' => 'required|integer',
            'reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        return DB::transaction(function () use ($request) {
            $product = InventoryProduct::where('id', $request->product_id)->lockForUpdate()->first();
            $adjustQty = (int) $request->quantity;

            $stockBefore = $product->stock_quantity;
            $stockAfter = max(0, $stockBefore + $adjustQty);
            $newStatus = ($stockAfter <= 0) ? 'OUT_OF_STOCK' : 'AVAILABLE';

            $product->update([
                'stock_quantity' => $stockAfter,
                'status' => $newStatus
            ]);

            StockAdjustment::create([
                'product_id' => $product->id,
                'type' => ($adjustQty >= 0) ? 'ADD' : 'DEDUCT',
                'quantity' => $adjustQty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $request->reason ?? 'Admin Restock / Correction',
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated for ' . $product->name . '! New stock balance: ' . $stockAfter
            ]);
        });
    }

    /**
     * Admin Create / Update Product.
     */
    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cost_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->has('product_id') && !empty($request->product_id)) {
            $product = InventoryProduct::find($request->product_id);
            if ($product) {
                $product->update($request->only(['name', 'sku', 'cost_price', 'selling_price', 'stock_quantity', 'reorder_level', 'status']));
                return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
            }
        }

        InventoryProduct::create([
            'category_id' => $request->category_id ?? 1,
            'name' => $request->name,
            'sku' => $request->sku ?? ('SKU-' . strtoupper(substr($request->name, 0, 3)) . '-' . rand(100, 999)),
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'stock_quantity' => $request->stock_quantity,
            'reorder_level' => $request->reorder_level,
            'status' => ($request->stock_quantity > 0) ? 'AVAILABLE' : 'OUT_OF_STOCK'
        ]);

        return response()->json(['success' => true, 'message' => 'New F&B product added!']);
    }
}
