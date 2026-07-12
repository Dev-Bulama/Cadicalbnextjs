@php
    $section = \App\Models\HomeSection::content('coverage', ['meta' => [], 'items' => []]);
    $cities = $section['items'];
    $meta = $section['meta'];
    $coverageImage = $meta['image'] ?? 'deliveries.png';
    $coverageImageUrl = str_starts_with($coverageImage, '/storage') ? url($coverageImage) : asset($coverageImage);
@endphp
<section class="py-20 px-4 md:px-8 bg-cadical-700 relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative z-10 max-w-5xl mx-auto grid md:grid-cols-2 gap-14 items-center">
        <div x-reveal>
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="map-pin" class="w-4 h-4 text-accent"></i>
                <p class="text-white/60 text-xs font-semibold uppercase tracking-widest">{{ $meta['eyebrow'] ?? '' }}</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">{{ $meta['heading'] ?? '' }} <span class="italic text-teal-300">{{ $meta['heading_accent'] ?? '' }}</span></h2>
            <p class="text-white/65 mb-8 leading-relaxed">{{ $meta['paragraph'] ?? '' }}</p>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($cities as $c)
                    <div class="flex items-center justify-between bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2.5">
                        <span class="text-white text-sm font-medium">{{ $c['city'] }}</span>
                        <span class="text-teal-300 text-xs font-semibold">{{ $c['time'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-reveal="0.1" class="flex justify-center">
            <div class="relative w-[300px] md:w-[360px] aspect-square rounded-2xl overflow-hidden border border-white/20 shadow-2xl">
                <img src="{{ $coverageImageUrl }}" alt="Nationwide Delivery" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-tr from-cadical-700/40 to-transparent"></div>
            </div>
        </div>
    </div>
</section>
