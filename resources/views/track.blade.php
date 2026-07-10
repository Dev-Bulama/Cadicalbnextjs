@extends('layouts.app')
@section('content')
<div x-data="{
        code: '{{ request('code') }}', order: null, loading: false, notFound: false,
        async search() {
            if (!this.code.trim()) return;
            this.loading = true; this.notFound = false; this.order = null;
            try {
                const res = await fetch('{{ url('/api/track') }}/' + encodeURIComponent(this.code.trim()));
                if (!res.ok) { this.notFound = true; return; }
                this.order = await res.json();
                $nextTick(() => window.lucide && window.lucide.createIcons());
            } finally { this.loading = false; }
        },
    }"
    x-init="if (code) search()"
    class="min-h-screen bg-slate-50 pt-24 pb-16 px-4">

    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-2">Order Tracking</p>
            <h1 class="text-3xl font-bold text-slate-900">Track Your Order</h1>
            <p class="text-slate-500 mt-2">Enter your tracking code to see the latest status.</p>
        </div>

        <form @submit.prevent="search" class="flex items-center gap-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-lg mx-auto p-1.5 mb-10">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 ml-2 flex-shrink-0"></i>
            <input x-model="code" placeholder="e.g. TRK-AB12CD-3456" class="flex-1 text-sm outline-none py-2 bg-transparent">
            <button type="submit" class="bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors">Track</button>
        </form>

        <template x-if="loading">
            <p class="text-center text-slate-400">Searching…</p>
        </template>

        <template x-if="notFound">
            <div class="text-center py-10">
                <i data-lucide="package-x" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
                <p class="text-slate-500">No order found with that tracking code.</p>
            </div>
        </template>

        <template x-if="order">
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Order <span x-text="order.tracking_code"></span></h2>
                        </div>
                        <span class="bg-blue-100 text-cadical-500 text-xs font-semibold px-3 py-1 rounded-full" x-text="order.status"></span>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4 pt-2">
                        <div class="border border-slate-100 rounded-xl p-4 flex items-center gap-3">
                            <i data-lucide="package" class="w-5 h-5 text-cadical-500"></i>
                            <div>
                                <p class="text-xs text-slate-400">Carrier</p>
                                <p class="font-semibold text-sm text-slate-900" x-text="order.carrier || 'Not assigned'"></p>
                            </div>
                        </div>
                        <div class="border border-slate-100 rounded-xl p-4 flex items-center gap-3">
                            <i data-lucide="truck" class="w-5 h-5 text-cadical-500"></i>
                            <div>
                                <p class="text-xs text-slate-400">Tracking Number</p>
                                <p class="font-semibold text-sm text-slate-900" x-text="order.tracking_number || 'Pending'"></p>
                            </div>
                        </div>
                        <div class="border border-slate-100 rounded-xl p-4 flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-cadical-500"></i>
                            <div>
                                <p class="text-xs text-slate-400">Delivery Address</p>
                                <p class="font-semibold text-sm text-slate-900 line-clamp-2" x-text="order.shipping_address"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Tracking History</h3>
                    <template x-if="!order.tracking_events || order.tracking_events.length === 0">
                        <p class="text-slate-400">No tracking updates yet.</p>
                    </template>
                    <div class="space-y-6">
                        <template x-for="(event, i) in order.tracking_events" :key="i">
                            <div class="flex gap-4">
                                <div class="w-3 h-3 mt-2 rounded-full bg-cadical-500 flex-shrink-0"></div>
                                <div>
                                    <p class="font-semibold text-slate-900" x-text="event.status"></p>
                                    <p class="text-slate-500 text-sm" x-text="event.message"></p>
                                    <p class="text-sm text-slate-400" x-show="event.location" x-text="'📍 ' + event.location"></p>
                                    <p class="text-xs text-slate-400 mt-1" x-text="new Date(event.created_at).toLocaleString()"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection
