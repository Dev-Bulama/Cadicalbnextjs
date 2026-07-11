<div>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search clinicians…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <div class="flex gap-2">
            @foreach (['all' => 'All', 'verified' => 'Verified', 'unverified' => 'Pending'] as $key => $label)
                <button wire:click="$set('filter', '{{ $key }}')" class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors {{ $filter === $key ? 'bg-cadical-500 text-white border-cadical-500' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($clinicians as $c)
            <div wire:key="clinician-{{ $c->id }}" class="bg-white rounded-2xl border border-slate-100 p-5 flex flex-col">
                <div class="flex justify-between items-start gap-2 mb-3">
                    <div>
                        <h3 class="font-semibold text-slate-900">{{ $c->first_name }} {{ $c->last_name }}</h3>
                        <p class="text-sm text-slate-400">{{ $c->specialization }}</p>
                    </div>
                    <x-admin.badge :color="$c->verified ? 'emerald' : 'amber'">{{ $c->verified ? 'Verified' : 'Pending' }}</x-admin.badge>
                </div>
                <div class="text-sm space-y-1.5 text-slate-600 flex-1 mb-4">
                    <p><span class="font-medium text-slate-900">License:</span> {{ $c->license_number ?: 'N/A' }}</p>
                    <p><span class="font-medium text-slate-900">Status:</span> {{ $c->is_available ? 'Available' : 'Unavailable' }}</p>
                    <p><span class="font-medium text-slate-900">Experience:</span> {{ $c->years_of_experience }} years</p>
                </div>
                @if (! $c->verified)
                    <div class="flex gap-2">
                        <button wire:click="verify({{ $c->id }})" class="flex-1 bg-cadical-500 hover:bg-cadical-700 text-white py-2 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Verify
                        </button>
                        <button wire:click="reject({{ $c->id }})" wire:confirm="Reject this clinician application?" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Reject
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-slate-400 col-span-full text-center py-12">No clinicians found.</p>
        @endforelse
    </div>
</div>
