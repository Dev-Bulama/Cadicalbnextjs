<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\BulkOrder;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = Supplier::where('user_id', $request->user()->id)->firstOrFail();

        $stats = [
            'active_products' => SupplierProduct::where('supplier_id', $supplier->id)->where('is_active', true)->count(),
            'pending_orders' => BulkOrder::where('supplier_id', $supplier->id)->whereIn('status', [BulkOrder::STATUS_SUBMITTED, BulkOrder::STATUS_NEGOTIATING])->count(),
            'monthly_revenue' => BulkOrder::where('supplier_id', $supplier->id)
                ->whereIn('status', [BulkOrder::STATUS_DELIVERED, BulkOrder::STATUS_PROCESSING, BulkOrder::STATUS_SHIPPED])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('final_amount'),
            'low_stock' => SupplierProduct::where('supplier_id', $supplier->id)->where('stock', '<', 5)->count(),
        ];

        $recentOrders = BulkOrder::where('supplier_id', $supplier->id)->latest()->limit(5)->get();

        return view('supplier.dashboard', compact('supplier', 'stats', 'recentOrders'));
    }
}
