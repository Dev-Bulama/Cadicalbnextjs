@php
    $statusColors = ['PENDING' => 'amber', 'APPROVED' => 'emerald', 'REJECTED' => 'red', 'SUSPENDED' => 'slate'];
@endphp
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <h3 class="font-bold text-lg text-slate-900">Company Profile</h3>
                <x-admin.badge :color="$statusColors[$supplier->status] ?? 'slate'">{{ $supplier->status }}</x-admin.badge>
            </div>
            @if (! $isEditing)
                <button wire:click="$set('isEditing', true)" class="h-8 px-3 border border-slate-200 rounded-lg text-xs font-semibold hover:bg-slate-50 flex items-center gap-1.5">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit
                </button>
            @endif
        </div>

        @if ($isEditing)
            <form wire:submit="save" class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="text-xs font-medium text-slate-500">Company Name</label><input wire:model="company_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">@error('company_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror</div>
                    <div><label class="text-xs font-medium text-slate-500">Contact Name</label><input wire:model="contact_name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Phone</label><input wire:model="phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">Alt. Phone</label><input wire:model="alt_phone" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-xs font-medium text-slate-500">Website</label><input wire:model="website" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">City</label><input wire:model="city" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div><label class="text-xs font-medium text-slate-500">State</label><input wire:model="state" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-xs font-medium text-slate-500">Address</label><input wire:model="address" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></div>
                    <div class="col-span-2"><label class="text-xs font-medium text-slate-500">Description</label><textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></textarea></div>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save Changes</button>
                    <button type="button" wire:click="$set('isEditing', false)" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                </div>
            </form>
        @else
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-slate-400">Company Name</p><p class="text-slate-900 font-medium">{{ $supplier->company_name }}</p></div>
                <div><p class="text-xs text-slate-400">Contact Name</p><p class="text-slate-900 font-medium">{{ $supplier->contact_name }}</p></div>
                <div><p class="text-xs text-slate-400">Email</p><p class="text-slate-900 font-medium">{{ $supplier->email }}</p></div>
                <div><p class="text-xs text-slate-400">Phone</p><p class="text-slate-900 font-medium">{{ $supplier->phone }}</p></div>
                <div><p class="text-xs text-slate-400">Website</p><p class="text-slate-900 font-medium">{{ $supplier->website ?: '—' }}</p></div>
                <div><p class="text-xs text-slate-400">Location</p><p class="text-slate-900 font-medium">{{ $supplier->city }}, {{ $supplier->state }}</p></div>
                <div class="col-span-2"><p class="text-xs text-slate-400">Address</p><p class="text-slate-900 font-medium">{{ $supplier->address }}</p></div>
                <div class="col-span-2"><p class="text-xs text-slate-400">Description</p><p class="text-slate-700">{{ $supplier->description ?: 'No description added yet.' }}</p></div>
            </div>
        @endif
    </div>
</div>
