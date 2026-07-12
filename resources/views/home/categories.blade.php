@php
    $section = \App\Models\HomeSection::content('categories', ['meta' => [], 'items' => []]);
    $cats = $section['items'];
    $meta = $section['meta'];
@endphp
<section class="py-16 px-4 md:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-2">{{ $meta['eyebrow'] ?? '' }}</p>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $meta['heading'] ?? '' }}</h2>
            </div>
            <a href="{{ url('/products') }}" class="text-sm text-cadical-500 font-semibold hover:underline hidden sm:block">View all &rarr;</a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-9 gap-3">
            @foreach ($cats as $i => $cat)
                <div x-reveal="{{ $i * 0.05 }}">
                    <a href="{{ url($cat['href']) }}" class="flex flex-col items-center gap-2 p-3 md:p-4 rounded-xl border {{ $cat['color'] }} hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center group">
                        <div class="p-2.5 rounded-lg bg-white/60 group-hover:bg-white transition-colors">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">{{ $cat['name'] }}</span>
                        <span class="text-[10px] text-slate-500 leading-tight hidden md:block">{{ $cat['sub'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-6 sm:hidden text-center">
            <a href="{{ url('/products') }}" class="text-sm text-cadical-500 font-semibold hover:underline">View all categories &rarr;</a>
        </div>
    </div>
</section>
