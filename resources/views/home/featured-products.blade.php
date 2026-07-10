@php $featured = \App\Models\Product::latest()->limit(8)->get(); @endphp
<section class="py-20 px-4 md:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-2">MediStore</p>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Featured Products</h2>
            </div>
            <a href="{{ url('/products') }}" class="text-sm text-cadical-500 font-semibold hover:underline hidden sm:flex items-center gap-1">View all <i data-lucide="arrow-right" class="w-[13px] h-[13px]"></i></a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="sm:hidden mt-8 text-center">
            <a href="{{ url('/products') }}" class="inline-flex items-center gap-1 text-sm text-cadical-500 font-semibold hover:underline">View all products <i data-lucide="arrow-right" class="w-[13px] h-[13px]"></i></a>
        </div>
    </div>
</section>
