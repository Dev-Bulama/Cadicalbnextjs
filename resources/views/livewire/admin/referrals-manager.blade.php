@php
    $urgencyColors = ['ROUTINE' => 'slate', 'URGENT' => 'amber', 'EMERGENCY' => 'red'];
@endphp
<div>
    <div class="grid grid-cols-2 gap-4 mb-6 max-w-md">
        <x-admin.stat-card icon="git-branch" label="Total Referrals" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="alert-triangle" label="Urgent" value="{{ $stats['urgent'] }}" color="red" />
    </div>

    <div class="flex gap-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search referrals…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <select wire:model.live="urgencyFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All urgency</option>
            <option value="ROUTINE">Routine</option>
            <option value="URGENT">Urgent</option>
            <option value="EMERGENCY">Emergency</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Ref ID</th>
                    <th class="px-4 py-3 font-medium">Referrer</th>
                    <th class="px-4 py-3 font-medium">Client Facility</th>
                    <th class="px-4 py-3 font-medium">Location</th>
                    <th class="px-4 py-3 font-medium">Urgency</th>
                    <th class="px-4 py-3 font-medium">Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($referrals as $ref)
                    <tr wire:key="ref-{{ $ref->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $ref->ref_id }}</td>
                        <td class="px-4 py-3">
                            <p class="text-slate-700">{{ $ref->referrer_full_name }}</p>
                            <p class="text-xs text-slate-400">{{ $ref->referrer_facility }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-slate-700">{{ $ref->client_facility_name }}</p>
                            <p class="text-xs text-slate-400">{{ $ref->client_phone }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $ref->client_lga }}, {{ $ref->client_state }}</td>
                        <td class="px-4 py-3"><x-admin.badge :color="$urgencyColors[$ref->urgency_level] ?? 'slate'">{{ $ref->urgency_level }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-slate-500">{{ $ref->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No referrals found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $referrals->links() }}</div>
</div>
