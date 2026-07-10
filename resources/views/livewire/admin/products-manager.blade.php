@php $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500'; @endphp
<div>
    <div class="flex items-center justify-between mb-6">
        <div class="relative w-72">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or SKU…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <button wire:click="openCreate" class="flex items-center gap-2 bg-cadical-500 hover:bg-cadical-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Product
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Image</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">SKU</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Stock</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($products as $product)
                    <tr wire:key="product-{{ $product->id }}">
                        <td class="px-4 py-3">
                            @if ($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] text-slate-400">N/A</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $product->sku }}</td>
                        <td class="px-4 py-3 text-slate-900">&#8358;{{ number_format($product->price) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $product->stock }}</td>
                        <td class="px-4 py-3"><x-admin.badge color="blue">{{ $product->category }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="openEdit({{ $product->id }})" class="text-xs font-semibold text-cadical-500 hover:underline">Edit</button>
                            <button wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this product?" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">No products found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>

    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
            <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-5">{{ $editingId ? 'Edit Product' : 'Create Product' }}</h3>

                <div class="space-y-4">
                    <div><input wire:model="name" placeholder="Product name" class="{{ $inputClass }}">@error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
                    <div><textarea wire:model="description" placeholder="Product description" rows="3" class="{{ $inputClass }}"></textarea></div>
                    <div><input wire:model="sku" placeholder="SKU" class="{{ $inputClass }}">@error('sku')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><input type="number" wire:model="price" placeholder="Price" class="{{ $inputClass }}">@error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
                        <div><input type="number" wire:model="stock" placeholder="Stock" class="{{ $inputClass }}">@error('stock')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror</div>
                    </div>
                    <div>
                        <select wire:model="category" class="{{ $inputClass }}">
                            <option value="">Select category</option>
                            @foreach (\App\Livewire\Admin\ProductsManager::CATEGORIES as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <input type="file" wire:model="image" accept="image/*" class="text-sm">
                        <div wire:loading wire:target="image" class="text-xs text-slate-400">Uploading…</div>
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-40 rounded-lg object-cover border border-slate-100">
                        @elseif ($existingImage)
                            <img src="{{ asset('storage/'.$existingImage) }}" class="w-full h-40 rounded-lg object-cover border border-slate-100">
                        @endif
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button wire:click="$set('showModal', false)" class="flex-1 border border-slate-200 text-slate-600 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                        <button wire:click="save" wire:loading.attr="disabled" class="flex-1 bg-cadical-500 hover:bg-cadical-700 disabled:opacity-70 text-white py-2.5 rounded-lg text-sm font-semibold transition-colors">
                            <span wire:loading.remove>{{ $editingId ? 'Update Product' : 'Create Product' }}</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
