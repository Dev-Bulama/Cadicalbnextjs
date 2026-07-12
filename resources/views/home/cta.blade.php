@php $meta = \App\Models\HomeSection::content('cta', ['meta' => []])['meta']; @endphp
<section id="contact" class="py-20 px-4 md:px-8 bg-cadical-700 relative overflow-hidden">
    <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 60px 60px;"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px]" style="background: radial-gradient(circle, rgba(245,166,35,0.12) 0%, transparent 70%)"></div>

    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <span class="inline-block text-white/80 text-xs font-semibold tracking-widest uppercase border border-white/20 bg-white/10 px-4 py-1.5 rounded-full mb-6">{{ $meta['badge'] ?? '' }}</span>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">{{ $meta['headline'] ?? '' }}</h2>
        <p class="text-white/65 mb-10 text-base leading-relaxed max-w-lg mx-auto">{{ $meta['sub'] ?? '' }}</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url($meta['button1_href'] ?? '/auth/register') }}" class="flex items-center gap-2 bg-accent hover:bg-accent-600 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:scale-105 shadow-lg">
                {{ $meta['button1_label'] ?? '' }} <i data-lucide="arrow-right" class="w-[15px] h-[15px]"></i>
            </a>
            <a href="{{ $meta['button2_href'] ?? '#' }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/30 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all">
                <i data-lucide="message-circle" class="w-[15px] h-[15px]"></i> {{ $meta['button2_label'] ?? '' }}
            </a>
        </div>
    </div>
</section>
