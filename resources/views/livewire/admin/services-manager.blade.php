<div>
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $services->count() }} services listed</p>
        <button wire:click="create" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Service
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($services as $service)
            <div wire:key="svc-{{ $service->id }}" class="bg-white rounded-2xl border border-slate-100 p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-cadical-500/10 flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $service->icon ?: 'wrench' }}" class="w-5 h-5 text-cadical-500"></i>
                    </div>
                    <x-admin.badge :color="$service->is_active ? 'emerald' : 'slate'">{{ $service->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                </div>
                <p class="font-semibold text-sm text-slate-900 mt-3">{{ $service->name }}</p>
                <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $service->description }}</p>
                <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-wide">{{ $service->category }}</p>
                <div class="flex items-center gap-1.5 mt-3">
                    <button wire:click="edit({{ $service->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Edit</button>
                    <button wire:click="toggleActive({{ $service->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">{{ $service->is_active ? 'Deactivate' : 'Activate' }}</button>
                    <button wire:click="delete({{ $service->id }})" wire:confirm="Delete this service?" class="h-7 px-2.5 text-red-500 hover:bg-red-50 rounded-md text-xs font-semibold">Delete</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 col-span-full text-center py-12">No services found.</p>
        @endforelse
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data @keydown.escape.window="$wire.set('showModal', false)">
            <div class="absolute inset-0 bg-slate-900/40" wire:click="$set('showModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-bold text-lg text-slate-900 mb-4">{{ $editingId ? 'Edit Service' : 'Add Service' }}</h3>
                <form wire:submit="save" class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-slate-500">Name</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-slate-500">Category</label>
                            <select wire:model="category" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500">Icon (lucide name)</label>
                            <input type="text" wire:model="icon" placeholder="stethoscope" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-slate-500">Display Order</label>
                            <input type="number" wire:model="order" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        </div>
                        <label class="flex items-center gap-2 mt-6">
                            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300">
                            <span class="text-sm text-slate-700">Active</span>
                        </label>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save</button>
                        <button type="button" wire:click="$set('showModal', false)" class="flex-1 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
