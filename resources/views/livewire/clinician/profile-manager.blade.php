<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-center">
            <div class="w-20 h-20 rounded-full bg-cadical-500 text-white flex items-center justify-center text-xl font-bold shrink-0">
                {{ strtoupper(substr($clinician->first_name, 0, 1) . substr($clinician->last_name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-slate-900">{{ $clinician->first_name }} {{ $clinician->last_name }}</h2>
                <p class="text-cadical-500 font-medium text-sm">{{ $clinician->specialization }}</p>
                <div class="flex gap-2 flex-wrap mt-2">
                    <x-admin.badge :color="$clinician->verified ? 'emerald' : 'slate'">{{ $clinician->verified ? '✓ Verified' : 'Pending Verification' }}</x-admin.badge>
                    <x-admin.badge :color="$clinician->is_available ? 'emerald' : 'slate'">{{ $clinician->is_available ? 'Available for Hire' : 'Unavailable' }}</x-admin.badge>
                </div>
            </div>
            @if (! $isEditing)
                <button wire:click="$set('isEditing', true)" class="h-9 px-3 border border-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 flex items-center gap-1.5 shrink-0">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit Profile
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Professional Information</h3>

        @if ($isEditing)
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="text-xs font-medium text-slate-500">First Name</label><input wire:model="first_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Last Name</label><input wire:model="last_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Specialization</label><input wire:model="specialization" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Years of Experience</label><input type="number" wire:model="years_of_experience" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-500">Professional Bio</label>
                    <textarea wire:model="bio" rows="4" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></textarea>
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_available" class="rounded border-slate-300">
                    <span class="text-sm text-slate-700">Available for hire</span>
                </label>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save Changes</button>
                    <button type="button" wire:click="$set('isEditing', false)" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                </div>
            </form>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
                <div><label class="text-sm font-medium text-slate-500">First Name</label><p class="text-slate-900 mt-1">{{ $clinician->first_name }}</p></div>
                <div><label class="text-sm font-medium text-slate-500">Last Name</label><p class="text-slate-900 mt-1">{{ $clinician->last_name }}</p></div>
                <div><label class="text-sm font-medium text-slate-500">Specialization</label><p class="text-slate-900 mt-1">{{ $clinician->specialization }}</p></div>
                <div><label class="text-sm font-medium text-slate-500">License Number</label><p class="text-slate-900 mt-1">{{ $clinician->license_number ?: 'Not provided' }}</p></div>
                <div><label class="text-sm font-medium text-slate-500">Years of Experience</label><p class="text-slate-900 mt-1">{{ $clinician->years_of_experience }} years</p></div>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-500">Professional Bio</label>
                <p class="text-slate-700 mt-1">{{ $clinician->bio ?: 'No bio added yet' }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Account Information</h3>
        <label class="text-sm font-medium text-slate-500">Email Address</label>
        <p class="text-slate-900 mt-1">{{ $clinician->user->email }}</p>
        <p class="text-xs text-slate-400 mt-1">Contact email cannot be changed</p>
    </div>
</div>
