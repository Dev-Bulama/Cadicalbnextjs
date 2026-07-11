@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Analytics</h1>
        <p class="text-slate-500 text-sm">Revenue, orders and marketplace performance</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card icon="banknote" label="Total Revenue" value="₦{{ number_format($summary['total_revenue'], 0) }}" color="cadical" />
        <x-admin.stat-card icon="receipt" label="Avg Order Value" value="₦{{ number_format($summary['avg_order_value'], 0) }}" color="emerald" />
        <x-admin.stat-card icon="truck" label="Suppliers" value="{{ $summary['total_suppliers'] }}" color="amber" />
        <x-admin.stat-card icon="file-text" label="Open RFQs" value="{{ $summary['open_rfqs'] }}" color="cadical" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4">Revenue (last 6 months)</h3>
            <canvas id="revenueChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4">Orders per Month</h3>
            <canvas id="ordersChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4">Orders by Status</h3>
            <canvas id="statusChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <h3 class="font-semibold text-sm text-slate-900 mb-4">Top Product Categories</h3>
            <canvas id="categoryChart" height="220"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const chartData = @json($chart);
    const palette = ['#1565C0', '#F5A623', '#22c55e', '#ef4444', '#8b5cf6', '#0ea5e9'];

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{ label: 'Revenue (₦)', data: chartData.revenue, borderColor: '#1565C0', backgroundColor: 'rgba(21,101,192,0.1)', fill: true, tension: 0.3 }],
        },
        options: { responsive: true, plugins: { legend: { display: false } } },
    });

    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [{ label: 'Orders', data: chartData.orders, backgroundColor: '#F5A623', borderRadius: 6 }],
        },
        options: { responsive: true, plugins: { legend: { display: false } } },
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.orderStatusLabels,
            datasets: [{ data: chartData.orderStatusData, backgroundColor: palette }],
        },
        options: { responsive: true },
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: chartData.categoryLabels,
            datasets: [{ label: 'Products', data: chartData.categoryData, backgroundColor: '#1565C0', borderRadius: 6 }],
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } },
    });
</script>
@endsection
