@php
    $stats = \App\Models\HomeSection::content('stats', ['items' => []])['items'];
@endphp
<section class="py-16 px-4 md:px-8 bg-cadical-700">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-4">
        @foreach ($stats as $i => $s)
            <div x-reveal="{{ $i * 0.1 }}" class="text-center"
                x-data="{ count: 0, target: {{ $s['value'] }} }"
                x-intersect.once="
                    let start = Date.now(); const duration = 1800;
                    const step = () => {
                        const elapsed = Date.now() - start;
                        const progress = Math.min(elapsed / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 3);
                        count = Math.floor(eased * target);
                        if (progress < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                ">
                <div class="text-4xl md:text-5xl font-bold text-white mb-1">
                    <span class="tabular-nums" x-text="count"></span>{{ $s['suffix'] }}
                </div>
                <div class="text-white font-semibold text-sm mb-0.5">{{ $s['label'] }}</div>
                <div class="text-white/50 text-xs">{{ $s['sub'] }}</div>
            </div>
        @endforeach
    </div>
</section>
