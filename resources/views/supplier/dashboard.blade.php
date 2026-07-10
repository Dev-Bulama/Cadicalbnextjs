@php
    $portalName = 'Cadical Supplier Portal';
    $portalSubtitle = $supplier->company_name;
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/supplier/dashboard'],
        ['name' => 'Products', 'href' => '/supplier/products'],
        ['name' => 'Orders', 'href' => '/supplier/orders'],
        ['name' => 'Profile', 'href' => '/supplier/profile'],
    ];
    $statusColors = ['DRAFT' => 'slate', 'SUBMITTED' => 'amber', 'NEGOTIATING' => 'amber', 'APPROVED' => 'cadical', 'PROCESSING' => 'cadical', 'SHIPPED' => 'amber', 'DELIVERED' => 'emerald', 'CANCELLED' => 'red'];
@endphp
@extends('layouts.portal')
@section('content')
<div class="p-6 sm:p-8 max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Supplier Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Welcome back. Here's your business overview.</p>
        </div>
        <x-admin.badge color="emerald">{{ ucfirst(strtolower($supplier->status)) }} Supplier</x-admin.badge>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-admin.stat-card icon="package" label="Active Products" value="{{ $stats['active_products'] }}" color="cadical" />
        <x-admin.stat-card icon="shopping-cart" label="Pending Orders" value="{{ $stats['pending_orders'] }}" color="amber" />
        <x-admin.stat-card icon="trending-up" label="Monthly Revenue" value="₦{{ number_format($stats['monthly_revenue'], 0) }}" color="emerald" />
        <x-admin.stat-card icon="alert-triangle" label="Low Stock Alerts" value="{{ $stats['low_stock'] }}" color="red" />
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm text-slate-900">Recent Orders</h3>
                <a href="{{ url('/supplier/orders') }}" class="text-xs text-cadical-500 font-medium flex items-center gap-1 hover:underline">View all <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
            </div>
            <div class="space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-slate-100">
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ $order->bulk_code }}</p>
                            <p class="text-xs text-slate-400">{{ $order->organization ?? $order->contact_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-900">₦{{ number_format($order->final_amount, 0) }}</p>
                            <x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status }}</x-admin.badge>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-8">No orders yet.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-semibold text-sm text-slate-900 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    @foreach ([['label' => 'Manage Products', 'href' => '/supplier/products', 'icon' => 'package'], ['label' => 'View Orders', 'href' => '/supplier/orders', 'icon' => 'shopping-cart'], ['label' => 'Edit Profile', 'href' => '/supplier/profile', 'icon' => 'star']] as $action)
                        <a href="{{ url($action['href']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition-colors">
                            <i data-lucide="{{ $action['icon'] }}" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-sm font-medium text-slate-700">{{ $action['label'] }}</span>
                            <i data-lucide="arrow-right" class="w-3 h-3 text-slate-300 ml-auto"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="star" class="w-4 h-4 text-amber-500"></i>
                    <p class="text-sm font-semibold text-slate-900">Performance</p>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between"><span class="text-slate-400">Overall Rating</span><span class="font-semibold text-slate-900">{{ number_format($supplier->rating, 1) }}/5.0</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Delivery Score</span><span class="font-semibold text-emerald-600">{{ number_format($supplier->delivery_score, 0) }}%</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Total Orders</span><span class="font-semibold text-slate-900">{{ $supplier->total_orders }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Total Products</span><span class="font-semibold text-slate-900">{{ $supplier->total_products }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
