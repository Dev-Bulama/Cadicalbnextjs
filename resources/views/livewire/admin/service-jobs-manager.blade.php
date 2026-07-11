@php
    $statusColors = [
        'ASSIGNED' => 'cadical', 'ACCEPTED' => 'cadical', 'REJECTED' => 'red', 'EN_ROUTE' => 'amber',
        'ON_SITE' => 'amber', 'IN_PROGRESS' => 'amber', 'WAITING_PARTS' => 'red', 'COMPLETED' => 'emerald', 'CANCELLED' => 'slate',
    ];
@endphp
<div>
    <div class="grid grid-cols-3 gap-4 mb-6 max-w-xl">
        <x-admin.stat-card icon="wrench" label="Total Jobs" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="loader" label="Active" value="{{ $stats['active'] }}" color="amber" />
        <x-admin.stat-card icon="check-circle" label="Completed" value="{{ $stats['completed'] }}" color="emerald" />
    </div>

    <div class="flex gap-3 mb-6">
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            @foreach (['ASSIGNED', 'ACCEPTED', 'EN_ROUTE', 'ON_SITE', 'IN_PROGRESS', 'WAITING_PARTS', 'COMPLETED', 'CANCELLED'] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="space-y-3">
        @forelse ($jobs as $job)
            <div wire:key="job-{{ $job->id }}" class="bg-white rounded-2xl border border-slate-100 p-4">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-sm text-slate-900">{{ $job->job_code }}</p>
                            <x-admin.badge :color="$statusColors[$job->status] ?? 'slate'">{{ $job->status }}</x-admin.badge>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            Technician: {{ $job->technician ? $job->technician->first_name.' '.$job->technician->last_name : 'Unassigned' }}
                        </p>
                        @if ($job->scheduled_at)
                            <p class="text-xs text-slate-400">Scheduled {{ $job->scheduled_at->format('M d, Y H:i') }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if (! $job->technician_id)
                            <button wire:click="startAssign({{ $job->id }})" class="h-7 px-3 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Assign</button>
                        @endif
                        @if (! in_array($job->status, ['COMPLETED', 'CANCELLED']))
                            <button wire:click="updateStatus({{ $job->id }}, 'COMPLETED')" class="h-7 px-3 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Mark Complete</button>
                            <button wire:click="updateStatus({{ $job->id }}, 'CANCELLED')" class="h-7 px-3 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Cancel</button>
                        @endif
                    </div>
                </div>

                @if ($assigningId === $job->id)
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2 flex-wrap">
                        @forelse ($technicians as $tech)
                            <button wire:click="assignTechnician({{ $job->id }}, {{ $tech->id }})" class="px-3 py-1.5 border border-slate-200 rounded-md text-xs font-medium hover:bg-cadical-500/5 hover:border-cadical-500">
                                {{ $tech->first_name }} {{ $tech->last_name }}
                            </button>
                        @empty
                            <p class="text-xs text-slate-400">No available technicians.</p>
                        @endforelse
                        <button wire:click="cancelAssign" class="text-xs text-slate-400 hover:text-slate-700">Cancel</button>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-slate-400 text-center py-12">No service jobs found.</p>
        @endforelse
    </div>
</div>
