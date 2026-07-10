<div class="min-h-screen flex flex-col bg-slate-50">
    <section class="bg-cadical-700 pt-24 pb-10 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-2">MediStore</p>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Medical Equipment &amp; Supplies</h1>
            <p class="text-white/65 max-w-xl text-sm">Browse 100+ certified medical devices, consumables and healthcare products. Delivered nationwide.</p>
        </div>
    </section>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 pb-24 lg:pb-8" x-data="{ showFilters: false }">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Filters --}}
            <aside :class="showFilters ? 'block' : 'hidden'" class="lg:block">
                <div class="border border-slate-100 rounded-2xl bg-white p-5 space-y-5 sticky top-20 shadow-sm">
                    <div class="flex items-center justify-between lg:hidden">
                        <h2 class="font-semibold text-lg">Filters</h2>
                        <button @click="showFilters = false"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-medium block">Search</label>
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
                                class="w-full pl-10 pr-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-medium block">Category</label>
                        <select wire:model.live="category" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                            <option value="">All Categories</option>
                            @foreach (['Imaging', 'Diagnostics', 'ICU', 'Surgery', 'Laboratory', 'Consumables', 'Monitoring', 'Dental', 'Rehabilitation'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-medium block">Max Price:</label>
                        <p class="text-sm text-slate-500">&#8358;{{ number_format($this->maxPrice) }}</p>
                        <input type="range" min="0" max="{{ $this->maxPriceLimit }}" step="1000" wire:model.live.debounce.300ms="maxPrice" class="w-full accent-cadical-500">
                    </div>

                    @if ($this->hasActiveFilters)
                        <button wire:click="clearFilters" class="w-full border border-slate-200 rounded-lg py-2 text-sm font-medium hover:bg-slate-50 transition-colors">Clear Filters</button>
                    @endif
                </div>
            </aside>

            {{-- Products --}}
            <section class="lg:col-span-3">
                <div class="flex items-center justify-between mb-6 lg:hidden">
                    <p class="text-sm text-slate-500">{{ $products->count() }} products</p>
                    <button @click="showFilters = !showFilters" class="flex items-center gap-2 border border-slate-200 rounded-lg px-3 py-1.5 text-sm">
                        <i data-lucide="filter" class="w-4 h-4"></i> Filters
                    </button>
                </div>

                <div wire:loading class="text-sm text-slate-400 mb-4">Loading…</div>

                @if ($products->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <i data-lucide="search" class="w-12 h-12 text-slate-200 mb-4"></i>
                        <h3 class="text-xl font-semibold">No products found</h3>
                        <p class="text-slate-500 mt-2 mb-6">Try changing your search or filters.</p>
                        <button wire:click="clearFilters" class="bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700">Clear Filters</button>
                    </div>
                @else
                    <div class="space-y-6">
                        <p class="text-sm text-slate-500">Showing {{ $products->count() }} product{{ $products->count() !== 1 ? 's' : '' }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                @include('partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </main>
</div>
