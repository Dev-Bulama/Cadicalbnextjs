@php
    $imageUrl = $product->image ? asset('storage/'.$product->image) : null;
    $catColors = [
        'Imaging' => 'bg-blue-50 text-blue-700',
        'Diagnostics' => 'bg-emerald-50 text-emerald-700',
        'ICU' => 'bg-red-50 text-red-700',
        'Surgery' => 'bg-violet-50 text-violet-700',
        'Laboratory' => 'bg-amber-50 text-amber-700',
        'Monitoring' => 'bg-cyan-50 text-cyan-700',
        'Dental' => 'bg-pink-50 text-pink-700',
        'Rehabilitation' => 'bg-orange-50 text-orange-700',
        'Consumables' => 'bg-slate-100 text-slate-600',
    ];
    $badgeClass = $catColors[$product->category] ?? 'bg-slate-100 text-slate-600';
@endphp
<div x-data="{ liked: false, adding: false }" class="group flex flex-col bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
    <div class="relative w-full aspect-[4/3] bg-gradient-to-br from-slate-50 to-blue-50 overflow-hidden flex items-center justify-center">
        @if ($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <i data-lucide="package" class="w-10 h-10 text-slate-200"></i>
        @endif

        <button @click="liked = !liked" class="absolute top-2.5 right-2.5 p-1.5 bg-white/90 backdrop-blur rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
            <i data-lucide="heart" class="w-[13px] h-[13px]" :class="liked ? 'fill-red-500 text-red-500' : 'text-slate-400'"></i>
        </button>

        @if ($product->stock === 0)
            <div class="absolute inset-0 bg-white/70 backdrop-blur-sm flex items-center justify-center">
                <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-full border">Out of Stock</span>
            </div>
        @elseif ($product->stock < 5)
            <span class="absolute top-2.5 left-2.5 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Only {{ $product->stock }} left</span>
        @else
            <span class="absolute top-2.5 left-2.5 bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">In Stock</span>
        @endif
    </div>

    <div class="flex flex-col flex-1 p-4">
        <span class="self-start text-[10px] font-semibold px-2 py-0.5 rounded-full mb-2 {{ $badgeClass }}">{{ $product->category }}</span>
        <h3 class="font-semibold text-sm text-slate-900 line-clamp-2 leading-snug mb-2 flex-1 group-hover:text-cadical-500 transition-colors">{{ $product->name }}</h3>
        <p class="text-lg font-bold text-slate-900 mb-3">&#8358;{{ number_format($product->price) }}</p>

        <button
            @click="if (adding || {{ $product->stock }} === 0) return; adding = true; $store.cart.addToCart({ id: {{ $product->id }}, name: {{ Illuminate\Support\Js::from($product->name) }}, price: {{ (float) $product->price }}, category: {{ Illuminate\Support\Js::from($product->category) }}, image: {{ Illuminate\Support\Js::from($imageUrl) }} }); $dispatch('cart-toast', { message: {{ Illuminate\Support\Js::from($product->name.' added to cart') }} }); setTimeout(() => adding = false, 600)"
            @disabled($product->stock === 0)
            class="w-full flex items-center justify-center gap-2 bg-cadical-500 hover:bg-cadical-700 disabled:bg-slate-100 disabled:text-slate-400 text-white text-xs font-semibold py-2.5 rounded-xl transition-all">
            <i data-lucide="shopping-cart" class="w-[13px] h-[13px]" :class="adding && 'animate-bounce'"></i>
            <span x-text="adding ? 'Adding…' : {{ $product->stock === 0 ? "'Out of Stock'" : "'Add to Cart'" }}"></span>
        </button>
    </div>
</div>
