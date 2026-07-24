@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-1">Dashboard</h1>
        <p class="text-slate-500">Welcome back. Here's what's happening with your business today.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-admin.stat-card icon="banknote" label="Total Revenue" value="₦{{ number_format($stats['total_revenue']) }}" color="emerald" />
        <x-admin.stat-card icon="shopping-cart" label="Total Orders" value="{{ number_format($stats['total_orders']) }}" color="cadical" />
        <x-admin.stat-card icon="clock" label="Pending Orders" value="{{ number_format($stats['pending_orders']) }}" color="amber" />
        <x-admin.stat-card icon="truck" label="Pending Suppliers" value="{{ number_format($stats['pending_suppliers']) }}" color="violet" />
        <x-admin.stat-card icon="file-text" label="Open RFQs" value="{{ number_format($stats['open_rfqs']) }}" color="cadical" />
        <x-admin.stat-card icon="calendar" label="Active Bookings" value="{{ number_format($stats['active_bookings']) }}" color="cyan" />
        <x-admin.stat-card icon="wrench" label="Active Service Jobs" value="{{ number_format($stats['active_service_jobs']) }}" color="rose" />
        <x-admin.stat-card icon="git-branch" label="Referrals" value="{{ number_format($stats['new_referrals']) }}" color="orange" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-slate-900 mb-4">Recent Orders</h3>
            <div class="space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-slate-900">{{ $order->customerName() }}</p>
                            <p class="text-slate-400 text-xs font-mono">{{ $order->tracking_code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">&#8358;{{ number_format($order->total_amount) }}</p>
                            <x-admin.badge :color="$order->status === 'PAID' ? 'emerald' : 'amber'">{{ $order->status }}</x-admin.badge>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No orders yet.</p>
                @endforelse
            </div>
            <a href="{{ url('/admin/orders') }}" class="block text-sm text-cadical-500 font-semibold mt-4 hover:underline">View all orders &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-slate-900 mb-4">Recent Service Bookings</h3>
            <div class="space-y-3">
                @forelse ($recentBookings as $booking)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-slate-900">{{ $booking->equipment_name }}</p>
                            <p class="text-slate-400 text-xs font-mono">{{ $booking->booking_code }}</p>
                        </div>
                        <x-admin.badge color="cadical">{{ str_replace('_', ' ', $booking->status) }}</x-admin.badge>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No service bookings yet.</p>
                @endforelse
            </div>
            <a href="{{ url('/admin/service-jobs') }}" class="block text-sm text-cadical-500 font-semibold mt-4 hover:underline">View all jobs &rarr;</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach ([
            ['href' => '/admin/bookings', 'icon' => 'calendar', 'title' => 'Bookings', 'desc' => 'View, create, and manage all your bookings in one place', 'color' => 'blue'],
            ['href' => '/admin/products', 'icon' => 'package', 'title' => 'Products', 'desc' => 'Update inventory, prices, and product information', 'color' => 'emerald'],
            ['href' => '/admin/referrals', 'icon' => 'git-branch', 'title' => 'Referrals', 'desc' => 'View your referrals', 'color' => 'violet'],
        ] as $card)
            <a href="{{ url($card['href']) }}" class="group bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-{{ $card['color'] }}-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-{{ $card['color'] }}-200 transition-colors">
                    <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6 text-{{ $card['color'] }}-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $card['title'] }}</h3>
                <p class="text-sm text-slate-500">{{ $card['desc'] }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
