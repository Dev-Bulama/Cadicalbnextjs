@php
    $section = \App\Models\HomeSection::content('services', ['meta' => [], 'items' => []]);
    $services = $section['items'];
    $meta = $section['meta'];
@endphp
<section id="services" class="py-20 px-4 md:px-8 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">{{ $meta['eyebrow'] ?? '' }}</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3">{{ $meta['heading'] ?? '' }}</h2>
            <p class="text-slate-500 max-w-xl mx-auto">{{ $meta['sub'] ?? '' }}</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($services as $i => $s)
                <div x-reveal="{{ $i * 0.1 }}" class="bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4 {{ $s['color'] }}">
                        <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-4 flex-1">{{ $s['desc'] }}</p>
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        @foreach ($s['tags'] as $t)
                            <span class="text-[10px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $t }}</span>
                        @endforeach
                    </div>
                    <a href="{{ url('/booking') }}" class="flex items-center gap-1.5 text-sm font-semibold text-cadical-500 hover:gap-2.5 transition-all">
                        {{ $s['cta'] }} <i data-lucide="arrow-right" class="w-[13px] h-[13px]"></i>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ url('/booking') }}" class="inline-flex items-center gap-2 bg-cadical-500 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-cadical-700 transition-colors shadow-lg shadow-blue-200">
                {{ $meta['cta_label'] ?? 'Book Any Service' }} <i data-lucide="arrow-right" class="w-[15px] h-[15px]"></i>
            </a>
        </div>
    </div>
</section>
