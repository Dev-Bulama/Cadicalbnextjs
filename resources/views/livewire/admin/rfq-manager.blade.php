@php
    $statusColors = ['OPEN' => 'emerald', 'CLOSED' => 'slate', 'AWARDED' => 'cadical', 'CANCELLED' => 'red'];
@endphp
<div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card icon="file-text" label="Total RFQs" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="clock" label="Open" value="{{ $stats['open'] }}" color="emerald" />
        <x-admin.stat-card icon="trophy" label="Awarded" value="{{ $stats['awarded'] }}" color="amber" />
        <x-admin.stat-card icon="x-circle" label="Closed" value="{{ $stats['closed'] }}" color="slate" />
    </div>

    <div class="flex gap-3 mb-6">
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            <option value="OPEN">Open</option>
            <option value="CLOSED">Closed</option>
            <option value="AWARDED">Awarded</option>
            <option value="CANCELLED">Cancelled</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">RFQ</th>
                    <th class="px-4 py-3 font-medium">Organization</th>
                    <th class="px-4 py-3 font-medium">Qty</th>
                    <th class="px-4 py-3 font-medium">Budget</th>
                    <th class="px-4 py-3 font-medium">Closing</th>
                    <th class="px-4 py-3 font-medium">Bids</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rfqs as $r)
                    <tr wire:key="rfq-{{ $r->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $r->rfq_code }}</p>
                            <p class="text-xs text-slate-400">{{ $r->title }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->organization ?? $r->contact_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->quantity }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->target_budget ? '₦'.number_format($r->target_budget, 0) : '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->closing_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->bids_count }}</td>
                        <td class="px-4 py-3"><x-admin.badge :color="$statusColors[$r->status] ?? 'slate'">{{ $r->status }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="open({{ $r->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">View</button>
                                @if ($r->status === 'OPEN')
                                    <button wire:click="updateStatus({{ $r->id }}, 'AWARDED')" class="h-7 px-2.5 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Award</button>
                                    <button wire:click="updateStatus({{ $r->id }}, 'CLOSED')" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Close</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-slate-400">No RFQs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($active)
        <div class="fixed inset-0 z-50 flex justify-end" x-data @keydown.escape.window="$wire.closeDrawer()">
            <div class="absolute inset-0 bg-slate-900/40" wire:click="closeDrawer"></div>
            <div class="relative w-full max-w-md bg-white h-full overflow-y-auto shadow-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-slate-900">{{ $active->rfq_code }}</h3>
                    <button wire:click="closeDrawer" class="text-slate-400 hover:text-slate-700"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <p class="text-sm font-semibold text-slate-900">{{ $active->title }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $active->description }}</p>
                <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                    <div><p class="text-xs text-slate-400">Contact</p><p class="text-slate-700">{{ $active->contact_name }}</p></div>
                    <div><p class="text-xs text-slate-400">Phone</p><p class="text-slate-700">{{ $active->contact_phone }}</p></div>
                    <div><p class="text-xs text-slate-400">Quantity</p><p class="text-slate-700">{{ $active->quantity }}</p></div>
                    <div><p class="text-xs text-slate-400">Budget</p><p class="text-slate-700">{{ $active->target_budget ? '₦'.number_format($active->target_budget, 0) : '—' }}</p></div>
                </div>

                <h4 class="font-semibold text-sm text-slate-900 mt-6 mb-2">Bids ({{ $active->bids->count() }})</h4>
                <div class="space-y-2">
                    @forelse ($active->bids as $bid)
                        <div class="border border-slate-100 rounded-lg p-3">
                            <p class="text-sm font-semibold text-slate-900">{{ $bid->supplier->company_name ?? 'Unknown supplier' }}</p>
                            <p class="text-xs text-slate-500">₦{{ number_format($bid->total_price, 0) }} · {{ $bid->lead_time_days }} days lead time</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">No bids submitted yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
