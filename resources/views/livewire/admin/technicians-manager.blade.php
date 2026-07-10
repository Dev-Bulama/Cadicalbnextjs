@php
    $statusColors = ['ACTIVE' => 'emerald', 'INACTIVE' => 'slate', 'ON_LEAVE' => 'amber', 'SUSPENDED' => 'red'];
@endphp
<div>
    <div class="grid grid-cols-3 gap-4 mb-6 max-w-xl">
        <x-admin.stat-card icon="users" label="Total Technicians" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="check-circle" label="Active" value="{{ $stats['active'] }}" color="emerald" />
        <x-admin.stat-card icon="briefcase" label="On Job" value="{{ $stats['onJob'] }}" color="amber" />
    </div>

    <div class="flex gap-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search technicians…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            <option value="ACTIVE">Active</option>
            <option value="INACTIVE">Inactive</option>
            <option value="ON_LEAVE">On Leave</option>
            <option value="SUSPENDED">Suspended</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($technicians as $tech)
            <div wire:key="tech-{{ $tech->id }}" class="bg-white rounded-2xl border border-slate-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-cadical-500/10 flex items-center justify-center shrink-0">
                        <i data-lucide="hard-hat" class="w-[18px] h-[18px] text-cadical-500"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-sm text-slate-900 truncate">{{ $tech->first_name }} {{ $tech->last_name }}</p>
                        <p class="text-xs text-slate-400">{{ $tech->city }}, {{ $tech->state }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <x-admin.badge :color="$statusColors[$tech->status] ?? 'slate'">{{ $tech->status }}</x-admin.badge>
                    @if ($tech->is_on_job)
                        <x-admin.badge color="amber">On Job</x-admin.badge>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-3 text-xs text-slate-400">
                    <span>{{ $tech->service_jobs_count }} jobs</span>
                    <span class="flex items-center gap-1"><i data-lucide="star" class="w-3 h-3 text-amber-500"></i>{{ number_format($tech->rating, 1) }}</span>
                </div>
                <div class="flex items-center gap-1.5 mt-3">
                    @if ($tech->status !== 'ACTIVE')
                        <button wire:click="updateStatus({{ $tech->id }}, 'ACTIVE')" class="h-7 px-2.5 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Activate</button>
                    @else
                        <button wire:click="updateStatus({{ $tech->id }}, 'SUSPENDED')" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Suspend</button>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 col-span-full text-center py-12">No technicians found.</p>
        @endforelse
    </div>
</div>
