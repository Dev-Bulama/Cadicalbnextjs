@php
    $statusColors = ['PENDING' => 'amber', 'APPROVED' => 'emerald', 'REJECTED' => 'red', 'SUSPENDED' => 'slate'];
@endphp
<div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card icon="truck" label="Total" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="clock" label="Pending KYC" value="{{ $stats['pending'] }}" color="amber" />
        <x-admin.stat-card icon="check-circle" label="Approved" value="{{ $stats['approved'] }}" color="emerald" />
        <x-admin.stat-card icon="x-circle" label="Rejected" value="{{ $stats['rejected'] }}" color="red" />
    </div>

    <div class="flex gap-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search suppliers…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            <option value="PENDING">Pending</option>
            <option value="APPROVED">Approved</option>
            <option value="REJECTED">Rejected</option>
            <option value="SUSPENDED">Suspended</option>
        </select>
    </div>

    <div class="space-y-3">
        @forelse ($suppliers as $s)
            <div wire:key="supplier-{{ $s->id }}" class="bg-white rounded-2xl border border-slate-100 p-4">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-cadical-500/10 flex items-center justify-center shrink-0">
                            <i data-lucide="building-2" class="w-[18px] h-[18px] text-cadical-500"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold text-sm text-slate-900">{{ $s->company_name }}</p>
                                <x-admin.badge :color="$statusColors[$s->status] ?? 'slate'">{{ $s->status }}</x-admin.badge>
                            </div>
                            <p class="text-xs text-slate-400">{{ $s->contact_name }}</p>
                            <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-400 flex-wrap">
                                <span class="flex items-center gap-1"><i data-lucide="mail" class="w-[11px] h-[11px]"></i>{{ $s->email }}</span>
                                <span class="flex items-center gap-1"><i data-lucide="phone" class="w-[11px] h-[11px]"></i>{{ $s->phone }}</span>
                                <span>{{ $s->state }}, Nigeria</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-center shrink-0">
                        <div><p class="text-sm font-bold text-slate-900">{{ $s->products_count }}</p><p class="text-[10px] text-slate-400">Products</p></div>
                        <div><p class="text-sm font-bold text-slate-900">{{ $s->rfq_bids_count }}</p><p class="text-[10px] text-slate-400">RFQ Bids</p></div>
                        <div><p class="text-sm font-bold text-slate-900 flex items-center gap-0.5 justify-center"><i data-lucide="star" class="w-[11px] h-[11px] text-amber-500"></i>{{ number_format($s->rating, 1) }}</p><p class="text-[10px] text-slate-400">Rating</p></div>
                        <div class="flex flex-col gap-1.5">
                            @if ($s->status === 'PENDING')
                                <button wire:click="updateStatus({{ $s->id }}, 'APPROVED')" class="h-7 px-3 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold transition-colors">Approve</button>
                                <button wire:click="updateStatus({{ $s->id }}, 'REJECTED')" class="h-7 px-3 bg-red-500 hover:bg-red-600 text-white rounded-md text-xs font-semibold transition-colors">Reject</button>
                            @elseif ($s->status === 'APPROVED')
                                <button wire:click="updateStatus({{ $s->id }}, 'SUSPENDED')" class="h-7 px-3 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50 transition-colors">Suspend</button>
                            @else
                                <button wire:click="updateStatus({{ $s->id }}, 'APPROVED')" class="h-7 px-3 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50 transition-colors">Reinstate</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-12">No suppliers found.</p>
        @endforelse
    </div>
</div>
