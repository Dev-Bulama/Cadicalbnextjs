@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">CRM Integration</h1>
        <p class="text-slate-500 text-sm">Connect Cadical Solutions to Zoho CRM for customer and pipeline sync</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cadical-500/10 flex items-center justify-center">
                    <i data-lucide="plug" class="w-5 h-5 text-cadical-500"></i>
                </div>
                <div>
                    <p class="font-semibold text-sm text-slate-900">Zoho CRM</p>
                    <p class="text-xs text-slate-400">Customer, order and RFQ synchronization</p>
                </div>
            </div>
            @if ($connection && $connection->is_connected)
                <x-admin.badge color="emerald">Connected</x-admin.badge>
            @else
                <x-admin.badge color="slate">Not Connected</x-admin.badge>
            @endif
        </div>

        @if ($connection)
            <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                <div><p class="text-xs text-slate-400">Health Score</p><p class="text-slate-700 font-medium">{{ $connection->health_score ?? '—' }}</p></div>
                <div><p class="text-xs text-slate-400">Sync Enabled</p><p class="text-slate-700 font-medium">{{ $connection->sync_enabled ? 'Yes' : 'No' }}</p></div>
                <div><p class="text-xs text-slate-400">Last Sync</p><p class="text-slate-700 font-medium">{{ $connection->last_sync_at?->diffForHumans() ?? 'Never' }}</p></div>
                <div><p class="text-xs text-slate-400">Next Sync</p><p class="text-slate-700 font-medium">{{ $connection->next_sync_at?->diffForHumans() ?? '—' }}</p></div>
            </div>
            @if ($connection->last_error)
                <div class="mt-4 bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-600">{{ $connection->last_error }}</div>
            @endif
        @else
            <p class="text-sm text-slate-500 mt-6">No Zoho connection has been configured yet. Full OAuth setup, field mapping and automated sync are part of a later phase.</p>
        @endif

        <button disabled class="mt-6 h-9 px-4 bg-slate-100 text-slate-400 rounded-lg text-sm font-semibold cursor-not-allowed">
            {{ $connection && $connection->is_connected ? 'Reconnect' : 'Connect to Zoho' }} (coming soon)
        </button>
    </div>
</div>
@endsection
