@php
    $statusConfig = [
        'ASSIGNED' => ['label' => 'Assigned', 'color' => 'text-cadical-700', 'bg' => 'bg-cadical-50 border-cadical-200'],
        'ACCEPTED' => ['label' => 'Accepted', 'color' => 'text-violet-700', 'bg' => 'bg-violet-50 border-violet-200'],
        'EN_ROUTE' => ['label' => 'En Route', 'color' => 'text-amber-700', 'bg' => 'bg-amber-50 border-amber-200'],
        'ON_SITE' => ['label' => 'On Site', 'color' => 'text-orange-700', 'bg' => 'bg-orange-50 border-orange-200'],
        'IN_PROGRESS' => ['label' => 'In Progress', 'color' => 'text-cadical-700', 'bg' => 'bg-cadical-50 border-cadical-200'],
        'WAITING_PARTS' => ['label' => 'Waiting Parts', 'color' => 'text-amber-700', 'bg' => 'bg-amber-50 border-amber-200'],
        'COMPLETED' => ['label' => 'Completed', 'color' => 'text-emerald-700', 'bg' => 'bg-emerald-50 border-emerald-200'],
        'REJECTED' => ['label' => 'Rejected', 'color' => 'text-red-700', 'bg' => 'bg-red-50 border-red-200'],
    ];
    $advanceLabel = [
        'ACCEPTED' => 'Mark En Route',
        'EN_ROUTE' => 'Mark On Site',
        'ON_SITE' => 'Start Work',
        'IN_PROGRESS' => 'Mark Complete',
    ];
@endphp
<div class="p-4">
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">My Jobs</h1>
        <p class="text-sm text-slate-500">{{ $active->count() }} active · {{ $completed->count() }} completed</p>
    </div>

    <div class="grid grid-cols-2 gap-2 mb-4">
        <div class="bg-white rounded-xl p-3 text-center border border-slate-100">
            <p class="text-2xl font-bold text-cadical-500">{{ $active->count() }}</p>
            <p class="text-xs text-slate-400">Active</p>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-slate-100">
            <p class="text-2xl font-bold text-emerald-600">{{ $completed->count() }}</p>
            <p class="text-xs text-slate-400">Done</p>
        </div>
    </div>

    <div class="flex gap-1.5 mb-4 bg-slate-100 rounded-lg p-1">
        <button wire:click="$set('tab', 'active')" class="flex-1 py-1.5 rounded-md text-sm font-medium {{ $tab === 'active' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500' }}">Active ({{ $active->count() }})</button>
        <button wire:click="$set('tab', 'completed')" class="flex-1 py-1.5 rounded-md text-sm font-medium {{ $tab === 'completed' ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500' }}">Completed ({{ $completed->count() }})</button>
    </div>

    @php $list = $tab === 'active' ? $active : $completed; @endphp

    @if ($list->isEmpty())
        <div class="text-center py-12 text-slate-400 text-sm">No {{ $tab }} jobs right now</div>
    @else
        @foreach ($list as $job)
            @php $sc = $statusConfig[$job->status] ?? $statusConfig['ASSIGNED']; @endphp
            <div wire:key="job-{{ $job->id }}" class="bg-white rounded-2xl border border-slate-100 p-4 mb-3">
                <div class="flex items-start justify-between mb-2">
                    <code class="text-xs text-slate-400">{{ $job->job_code }}</code>
                    <span class="text-[10px] px-2 py-0.5 rounded-full border font-medium {{ $sc['color'] }} {{ $sc['bg'] }}">{{ $sc['label'] }}</span>
                </div>

                <h3 class="font-semibold text-sm text-slate-900 mb-1">{{ $job->booking->equipment_name ?? 'Equipment' }}</h3>
                <p class="text-xs text-slate-500 mb-2 line-clamp-2">{{ $job->booking->issue_description ?? '' }}</p>

                <div class="flex items-center gap-1 text-xs text-slate-400 mb-1">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    <span>{{ $job->booking->site_address ?? '' }}, {{ $job->booking->site_city ?? '' }}</span>
                </div>
                <div class="flex items-center gap-1 text-xs text-slate-400">
                    <i data-lucide="user" class="w-3 h-3"></i>
                    <span>{{ $job->booking->user->name ?? 'Customer' }}</span>
                </div>

                @if ($job->scheduled_at)
                    <div class="mt-2 p-2 bg-slate-50 rounded-md flex items-center gap-1.5 text-xs text-slate-600">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Scheduled: {{ $job->scheduled_at->format('M d, Y H:i') }}</span>
                    </div>
                @endif

                <div class="flex gap-2 mt-3">
                    @if ($job->status === 'ASSIGNED')
                        <button wire:click="accept({{ $job->id }})" class="flex-1 h-8 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Accept Job</button>
                        <button wire:click="decline({{ $job->id }})" class="h-8 px-3 border border-red-200 text-red-600 hover:bg-red-50 rounded-md text-xs font-semibold">Decline</button>
                    @elseif (isset($advanceLabel[$job->status]))
                        <button wire:click="advance({{ $job->id }})" class="flex-1 h-8 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">{{ $advanceLabel[$job->status] }}</button>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
