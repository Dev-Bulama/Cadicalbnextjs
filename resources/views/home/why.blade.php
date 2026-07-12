@php
    $section = \App\Models\HomeSection::content('why', ['meta' => [], 'items' => []]);
    $pillars = $section['items'];
    $meta = $section['meta'];
    $whyImage = $meta['image'] ?? 'test.jpeg';
    $whyImageUrl = str_starts_with($whyImage, '/storage') ? url($whyImage) : asset($whyImage);
@endphp
<section id="why" class="py-20 px-4 md:px-8 bg-white">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">
        <div x-reveal class="relative">
            <div class="relative rounded-2xl overflow-hidden aspect-[4/3] shadow-2xl">
                <img src="{{ $whyImageUrl }}" alt="Why Cadical" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-cadical-700/60 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-[18px] h-[18px] text-cadical-500"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $meta['badge_title'] ?? '' }}</div>
                            <div class="text-xs text-slate-500">{{ $meta['badge_sub'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div x-reveal>
                <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">{{ $meta['eyebrow'] ?? '' }}</p>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3 leading-tight">
                    {{ $meta['heading'] ?? '' }}<br>
                    <span class="text-cadical-500">{{ $meta['heading_accent'] ?? '' }}</span>
                </h2>
                <p class="text-slate-500 mb-8 leading-relaxed">
                    {{ $meta['paragraph'] ?? '' }}
                </p>
            </div>

            <div class="space-y-4">
                @foreach ($pillars as $i => $p)
                    <div x-reveal="{{ $i * 0.1 }}" class="flex gap-4 group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $p['color'] }}">
                            <i data-lucide="{{ $p['icon'] }}" class="w-[18px] h-[18px]"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900 text-sm mb-0.5">{{ $p['title'] }}</div>
                            <div class="text-slate-500 text-sm leading-relaxed">{{ $p['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
