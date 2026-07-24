@php
    $section = \App\Models\HomeSection::content('partners', ['meta' => [], 'items' => []]);
    $partners = $section['items'];
    $meta = $section['meta'];
    // Duplicate the list so the marquee loops seamlessly (CSS animates -50%, i.e. one full copy).
    $marqueePartners = array_merge($partners, $partners);
@endphp
@if (! empty($partners))
<section class="py-14 px-4 md:px-8 bg-white border-y border-slate-100 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-2">{{ $meta['eyebrow'] ?? 'Our Partners' }}</p>
            <h2 class="text-xl md:text-2xl font-bold text-slate-900">{{ $meta['heading'] ?? 'Brands and manufacturers we work with' }}</h2>
        </div>
    </div>

    <div class="relative" x-data="{ paused: false }">
        <div class="absolute left-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

        <div class="flex gap-4 md:gap-6 w-max" :class="paused ? '' : 'animate-partners-marquee'" @mouseenter="paused = true" @mouseleave="paused = false">
            @foreach ($marqueePartners as $p)
                <div class="flex items-center justify-center h-20 w-44 md:h-24 md:w-52 shrink-0 bg-white border border-slate-100 rounded-xl px-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    @if (! empty($p['logo']))
                        <img src="{{ \App\Models\HomeSection::mediaUrl($p['logo']) }}" alt="{{ $p['name'] }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'" class="max-h-12 md:max-h-14 max-w-full object-contain">
                        <span class="hidden text-sm md:text-base font-bold text-slate-400 text-center leading-tight tracking-tight">{{ $p['name'] }}</span>
                    @else
                        <span class="text-sm md:text-base font-bold text-slate-400 text-center leading-tight tracking-tight">{{ $p['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes partners-marquee {
        from { transform: translateX(0); }
        to { transform: translateX(-50%); }
    }
    .animate-partners-marquee {
        animation: partners-marquee 40s linear infinite;
    }
</style>
@endif
