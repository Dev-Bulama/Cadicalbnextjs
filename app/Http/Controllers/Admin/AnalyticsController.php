<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->startOfMonth());

        $revenueByMonth = $months->map(function ($month) {
            return (float) Order::where('status', Order::STATUS_PAID)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
        });

        $ordersByMonth = $months->map(function ($month) {
            return Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });

        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $topCategories = Product::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'category');

        $chart = [
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->values(),
            'revenue' => $revenueByMonth->values(),
            'orders' => $ordersByMonth->values(),
            'orderStatusLabels' => $ordersByStatus->keys(),
            'orderStatusData' => $ordersByStatus->values(),
            'categoryLabels' => $topCategories->keys(),
            'categoryData' => $topCategories->values(),
        ];

        $summary = [
            'total_revenue' => Order::where('status', Order::STATUS_PAID)->sum('total_amount'),
            'avg_order_value' => (float) Order::where('status', Order::STATUS_PAID)->avg('total_amount'),
            'total_suppliers' => Supplier::count(),
            'open_rfqs' => Rfq::where('status', Rfq::STATUS_OPEN)->count(),
        ];

        return view('admin.analytics', compact('chart', 'summary'));
    }
}
