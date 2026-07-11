<div class="p-4 space-y-4">
    <h1 class="text-xl font-bold text-slate-900">Live Tracking</h1>

    <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-900">Share My Location</p>
            <p class="text-xs text-slate-400">Lets dispatch and customers see your live position while on a job</p>
        </div>
        <button wire:click="toggleShareLocation" class="w-11 h-6 rounded-full transition-colors relative shrink-0 {{ $shareLocation ? 'bg-emerald-500' : 'bg-slate-300' }}">
            <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full transition-transform {{ $shareLocation ? 'translate-x-[22px]' : 'translate-x-0.5' }}"></span>
        </button>
    </div>

    @if ($activeJob)
        <div class="bg-white rounded-2xl border border-slate-100 p-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-cadical-500/10 flex items-center justify-center">
                    <i data-lucide="navigation" class="w-4 h-4 text-cadical-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Active Job: {{ $activeJob->job_code }}</p>
                    <p class="text-xs text-slate-400">{{ str_replace('_', ' ', $activeJob->status) }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex items-start gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                    <span class="text-slate-700">{{ $activeJob->booking->site_address ?? '' }}, {{ $activeJob->booking->site_city ?? '' }}, {{ $activeJob->booking->site_state ?? '' }}</span>
                </div>
                <div class="flex items-start gap-2">
                    <i data-lucide="phone" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                    <span class="text-slate-700">{{ $activeJob->booking->site_phone ?? '—' }}</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 text-slate-400 text-sm">
            <i data-lucide="map-pin" class="w-6 h-6 mx-auto mb-2 opacity-40"></i>
            No active job in progress right now
        </div>
    @endif
</div>
