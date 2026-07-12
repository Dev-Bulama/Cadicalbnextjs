@php
    $section = \App\Models\HomeSection::content('compliance', ['meta' => [], 'items' => []]);
    $items = $section['items'];
    $meta = $section['meta'];
@endphp
<section id="compliance" class="py-16 px-4 md:px-8 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="shield-check" class="w-[18px] h-[18px] text-cadical-500"></i>
                    <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest">{{ $meta['eyebrow'] ?? '' }}</p>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $meta['heading'] ?? '' }} <span class="text-cadical-500 italic">{{ $meta['heading_accent'] ?? '' }}</span></h2>
            </div>
            <p class="text-slate-500 text-sm max-w-sm">{{ $meta['sub'] ?? '' }}</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($items as $i => $c)
                <div x-reveal="{{ $i * 0.08 }}" class="border border-slate-100 p-5 rounded-xl bg-slate-50 hover:shadow-sm transition-shadow">
                    <span class="inline-block text-[10px] font-bold bg-blue-100 text-cadical-500 px-2.5 py-1 rounded-full mb-3 uppercase tracking-widest">{{ $c['tag'] }}</span>
                    <h4 class="font-semibold text-slate-900 text-sm mb-1">{{ $c['title'] }}</h4>
                    <p class="text-xs text-slate-500">{{ $c['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
