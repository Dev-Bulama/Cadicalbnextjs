@php
    $typeConfig = [
        'order' => ['icon' => 'package', 'color' => 'text-cadical-500', 'bg' => 'bg-cadical-500/10'],
        'service' => ['icon' => 'wrench', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
        'payment' => ['icon' => 'credit-card', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
        'maintenance' => ['icon' => 'calendar', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
        'crm' => ['icon' => 'activity', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50'],
        'system' => ['icon' => 'bell', 'color' => 'text-slate-400', 'bg' => 'bg-slate-100'],
    ];
@endphp
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            <button wire:click="$set('filter', 'all')" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'all' ? 'bg-cadical-500 text-white' : 'bg-slate-100 text-slate-500 hover:text-slate-900' }}">All</button>
            <button wire:click="$set('filter', 'unread')" class="px-4 py-1.5 rounded-full text-sm font-medium {{ $filter === 'unread' ? 'bg-cadical-500 text-white' : 'bg-slate-100 text-slate-500 hover:text-slate-900' }}">
                Unread @if ($unreadCount > 0)<span class="ml-1.5 bg-white/20 text-xs px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>@endif
            </button>
        </div>
        @if ($unreadCount > 0)
            <button wire:click="markAllRead" class="text-xs text-cadical-500 font-medium flex items-center gap-1 hover:underline">
                <i data-lucide="check-check" class="w-3.5 h-3.5"></i> Mark all read
            </button>
        @endif
    </div>

    @if ($notifications->isEmpty())
        <div class="text-center py-16">
            <i data-lucide="bell" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
            <p class="font-medium text-sm text-slate-700">No notifications</p>
            <p class="text-xs text-slate-400 mt-1">You're all caught up!</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach ($notifications as $n)
                @php $cfg = $typeConfig[$n->type] ?? $typeConfig['system']; @endphp
                <div wire:key="notif-{{ $n->id }}" wire:click="markRead({{ $n->id }})" class="flex gap-3 p-4 rounded-xl border cursor-pointer transition-colors {{ $n->is_read ? 'bg-white border-slate-100' : 'bg-white border-cadical-500/20 shadow-sm' }}">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $cfg['bg'] }}">
                        <i data-lucide="{{ $cfg['icon'] }}" class="w-4 h-4 {{ $cfg['color'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm {{ $n->is_read ? 'font-medium' : 'font-semibold' }} text-slate-900">{{ $n->title }}</p>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if (! $n->is_read)
                                    <div class="w-2 h-2 rounded-full bg-cadical-500"></div>
                                @endif
                                <span class="text-[10px] text-slate-400">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $n->message }}</p>
                        @if ($n->action_url)
                            <a href="{{ url($n->action_url) }}" wire:click.stop class="inline-flex items-center gap-1 text-xs text-cadical-500 mt-1.5 hover:underline">
                                View details <i data-lucide="chevron-right" class="w-3 h-3"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
