<div class="p-4 space-y-4">
    <h1 class="text-xl font-bold text-slate-900">My Profile</h1>

    <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-cadical-500/10 flex items-center justify-center shrink-0">
            <i data-lucide="hard-hat" class="w-7 h-7 text-cadical-500"></i>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-slate-900">{{ $profile->first_name }} {{ $profile->last_name }}</p>
            <p class="text-xs text-slate-400">{{ $profile->city }}, {{ $profile->state }}</p>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="flex items-center gap-0.5 text-xs text-slate-600"><i data-lucide="star" class="w-3 h-3 text-amber-500"></i>{{ number_format($profile->rating, 1) }}</span>
                <span class="text-xs text-slate-400">· {{ $profile->completed_jobs }} jobs completed</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-slate-900">Availability</p>
            <p class="text-xs text-slate-400">Toggle off to stop receiving new job offers</p>
        </div>
        <button wire:click="toggleAvailability" class="w-11 h-6 rounded-full transition-colors relative {{ $is_available ? 'bg-emerald-500' : 'bg-slate-300' }}">
            <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full transition-transform {{ $is_available ? 'translate-x-[22px]' : 'translate-x-0.5' }}"></span>
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm text-slate-900">Contact Details</h3>
            @if (! $isEditing)
                <button wire:click="$set('isEditing', true)" class="text-xs text-cadical-500 font-medium flex items-center gap-1"><i data-lucide="edit-2" class="w-3 h-3"></i> Edit</button>
            @endif
        </div>

        @if ($isEditing)
            <form wire:submit="save" class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="text-xs font-medium text-slate-500">First Name</label><input wire:model="first_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Last Name</label><input wire:model="last_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Phone</label><input wire:model="phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">City</label><input wire:model="city" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-xs font-medium text-slate-500">State</label><input wire:model="state" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-xs font-medium text-slate-500">Base Address</label><input wire:model="base_address" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="submit" class="flex-1 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save</button>
                    <button type="button" wire:click="$set('isEditing', false)" class="flex-1 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                </div>
            </form>
        @else
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-400">Phone</span><span class="font-medium text-slate-900">{{ $profile->phone }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">City</span><span class="font-medium text-slate-900">{{ $profile->city }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">State</span><span class="font-medium text-slate-900">{{ $profile->state }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Base Address</span><span class="font-medium text-slate-900">{{ $profile->base_address ?: '—' }}</span></div>
            </div>
        @endif
    </div>
</div>
