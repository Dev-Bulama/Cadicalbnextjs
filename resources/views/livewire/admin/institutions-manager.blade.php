<div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <x-admin.stat-card icon="building-2" label="Total Institutions" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="hospital" label="Hospitals" value="{{ $stats['hospitals'] }}" color="emerald" />
        <x-admin.stat-card icon="stethoscope" label="Clinics" value="{{ $stats['clinics'] }}" color="amber" />
    </div>

    <div class="relative max-w-sm mb-6">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search institutions…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Institution</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">Location</th>
                    <th class="px-4 py-3 font-medium">Contact</th>
                    <th class="px-4 py-3 font-medium">RFQs</th>
                    <th class="px-4 py-3 font-medium">Bulk Orders</th>
                    <th class="px-4 py-3 font-medium">Maintenance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($institutions as $inst)
                    <tr wire:key="inst-{{ $inst->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $inst->inst_name }}</p>
                            <p class="text-xs text-slate-400">{{ $inst->email }}</p>
                        </td>
                        <td class="px-4 py-3"><x-admin.badge color="cadical">{{ $inst->inst_type }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->lga }}, {{ $inst->state }}</td>
                        <td class="px-4 py-3">
                            <p class="text-slate-700">{{ $inst->contact_name }}</p>
                            <p class="text-xs text-slate-400">{{ $inst->phone }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->rfqs_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->bulk_orders_count }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $inst->maintenance_schedules_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">No institutions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $institutions->links() }}</div>
</div>
