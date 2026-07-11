<div>
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">{{ $products->count() }} products listed</p>
        <button wire:click="create" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Product
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Product</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Price</th>
                    <th class="px-4 py-3 font-medium">Stock</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $p)
                    <tr wire:key="sp-{{ $p->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $p->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $p->category }}</td>
                        <td class="px-4 py-3 text-slate-600">₦{{ number_format($p->unit_price, 0) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $p->stock < 5 ? 'text-red-600 font-semibold' : 'text-slate-600' }}">{{ $p->stock }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.badge :color="$p->is_approved ? 'emerald' : 'amber'">{{ $p->is_approved ? 'Approved' : 'Pending Review' }}</x-admin.badge>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="edit({{ $p->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">Edit</button>
                                <button wire:click="delete({{ $p->id }})" wire:confirm="Remove this product?" class="h-7 px-2.5 text-red-500 hover:bg-red-50 rounded-md text-xs font-semibold">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No products yet — add your first one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data @keydown.escape.window="$wire.set('showModal', false)">
            <div class="absolute inset-0 bg-slate-900/40" wire:click="$set('showModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-bold text-lg text-slate-900 mb-4">{{ $editingId ? 'Edit Product' : 'Add Product' }}</h3>
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
                            <input type="text" wire:model="category" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500">Unit Price (₦)</label>
                            <input type="number" step="0.01" wire:model="unit_price" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-slate-500">Stock Quantity</label>
                            <input type="number" wire:model="stock" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
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
