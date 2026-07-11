<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Referral;
use App\Models\Rfq;
use App\Models\ServiceBooking;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_revenue' => Order::where('status', Order::STATUS_PAID)->sum('total_amount'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'pending_suppliers' => Supplier::where('status', Supplier::STATUS_PENDING)->count(),
            'open_rfqs' => Rfq::where('status', Rfq::STATUS_OPEN)->count(),
            'active_bookings' => Booking::count(),
            'active_service_jobs' => ServiceBooking::whereNotIn('status', [ServiceBooking::STATUS_COMPLETED, ServiceBooking::STATUS_CANCELLED])->count(),
            'new_referrals' => Referral::count(),
        ];

        $recentOrders = Order::with('user')->latest()->limit(5)->get();
        $recentBookings = ServiceBooking::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentBookings'));
    }
}
