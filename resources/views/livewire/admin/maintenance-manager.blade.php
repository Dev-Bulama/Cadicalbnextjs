<div>
    <div class="grid grid-cols-3 gap-4 mb-6 max-w-xl">
        <x-admin.stat-card icon="calendar-clock" label="Total Schedules" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="alert-triangle" label="Overdue" value="{{ $stats['overdue'] }}" color="red" />
        <x-admin.stat-card icon="clock" label="Due Soon" value="{{ $stats['dueSoon'] }}" color="amber" />
    </div>

    <div class="flex gap-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search equipment…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <div class="flex gap-1.5">
            <button wire:click="$set('filter', 'all')" class="px-3 py-2 rounded-lg text-xs font-semibold {{ $filter === 'all' ? 'bg-cadical-500 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">All</button>
            <button wire:click="$set('filter', 'overdue')" class="px-3 py-2 rounded-lg text-xs font-semibold {{ $filter === 'overdue' ? 'bg-red-500 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Overdue</button>
            <button wire:click="$set('filter', 'due_soon')" class="px-3 py-2 rounded-lg text-xs font-semibold {{ $filter === 'due_soon' ? 'bg-amber-500 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Due Soon</button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Equipment</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">Frequency</th>
                    <th class="px-4 py-3 font-medium">Technician</th>
                    <th class="px-4 py-3 font-medium">Next Due</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $s)
                    @php $overdue = $s->is_active && $s->next_due_date->isPast(); @endphp
                    <tr wire:key="sched-{{ $s->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900">{{ $s->equipment_name }}</p>
                            <p class="text-xs text-slate-400">{{ $s->schedule_code }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $s->service_type }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $s->frequency }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $s->technician ? $s->technician->first_name.' '.$s->technician->last_name : '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $overdue ? 'text-red-600 font-semibold' : 'text-slate-600' }}">{{ $s->next_due_date->format('M d, Y') }}</span>
                            @if ($overdue)
                                <x-admin.badge color="red">Overdue</x-admin.badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="markCompleted({{ $s->id }})" class="h-7 px-2.5 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Mark Completed</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No maintenance schedules found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
