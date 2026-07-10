@php
    $slides = [
        [
            'badge' => 'Medical Equipment Marketplace',
            'headline' => 'Premium Medical Equipment for Hospitals, Clinics & Healthcare Providers',
            'sub' => 'Source verified, certified medical devices from trusted suppliers across Nigeria.',
            'cta1' => ['label' => 'Shop Equipment', 'href' => '/products'],
            'cta2' => ['label' => 'Request Quote', 'href' => '/booking'],
            'image' => 'mri.jpeg',
            'gradient' => 'from-[#0D47A1]/90 via-[#1565C0]/80 to-[#1976D2]/70',
        ],
        [
            'badge' => 'Medical Services',
            'headline' => 'Installation, Maintenance & Repair Services Nationwide',
            'sub' => 'Certified biomedical engineers at your facility within 24–48 hours.',
            'cta1' => ['label' => 'Book a Service', 'href' => '/booking'],
            'cta2' => ['label' => 'View Service Plans', 'href' => '/booking'],
            'image' => 'test.jpeg',
            'gradient' => 'from-[#004D40]/90 via-[#00695C]/80 to-[#00796B]/70',
        ],
        [
            'badge' => 'Hospital Procurement',
            'headline' => 'Bulk Procurement Solutions for Healthcare Institutions',
            'sub' => 'Streamlined procurement with institutional pricing and dedicated account managers.',
            'cta1' => ['label' => 'Procurement Request', 'href' => '/institutional-portal'],
            'cta2' => ['label' => 'Talk to Specialist', 'href' => '/booking'],
            'image' => 'images/home-image.jpeg',
            'gradient' => 'from-[#1A237E]/90 via-[#283593]/80 to-[#303F9F]/70',
        ],
        [
            'badge' => 'Supplier Marketplace',
            'headline' => 'Trusted Medical Suppliers & Vendors Across Nigeria',
            'sub' => 'Partner with verified suppliers to grow your medical equipment business.',
            'cta1' => ['label' => 'Become a Supplier', 'href' => '/auth/register'],
            'cta2' => ['label' => 'Explore Products', 'href' => '/products'],
            'image' => 'images/Cadical.jpg',
            'gradient' => 'from-[#4A148C]/90 via-[#6A1B9A]/80 to-[#7B1FA2]/70',
        ],
    ];
@endphp
<section
    x-data="{
        slides: {{ Illuminate\Support\Js::from($slides) }},
        current: 0, paused: false, timer: null,
        start() { this.timer = setInterval(() => this.next(), 5500) },
        stop() { clearInterval(this.timer) },
        next() { this.current = (this.current + 1) % this.slides.length },
        prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length },
        go(i) { this.current = i },
    }"
    x-init="start()"
    class="relative w-full"
    style="height: calc(100vh - 4rem); min-height: 560px; max-height: 800px;"
>
    {{-- Sliding background --}}
    <div class="absolute inset-0 overflow-hidden">
        <template x-for="(slide, i) in slides" :key="i">
            <div x-show="current === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                <img :src="'{{ asset('') }}' + slide.image" :alt="slide.badge" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r" :class="slide.gradient"></div>
                <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 60px 60px;"></div>
            </div>
        </template>
    </div>

    {{-- Content overlay --}}
    <div class="absolute inset-0 z-10 flex flex-col justify-center px-6 md:px-16 max-w-5xl">
        <template x-for="(slide, i) in slides" :key="'text-'+i">
            <div x-show="current === i" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0" class="mb-8">
                <span class="inline-block text-white/90 text-xs font-semibold tracking-widest uppercase border border-white/30 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full mb-5" x-text="slide.badge"></span>
                <h1 class="text-white text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 max-w-3xl" x-text="slide.headline"></h1>
                <p class="text-white/75 text-sm md:text-lg leading-relaxed mb-6 max-w-xl" x-text="slide.sub"></p>
                <div class="flex flex-wrap gap-3">
                    <a :href="'{{ url('') }}' + slide.cta1.href" class="bg-accent hover:bg-accent-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-all hover:scale-105 shadow-lg" x-text="slide.cta1.label"></a>
                    <a :href="'{{ url('') }}' + slide.cta2.href" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/30 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-all" x-text="slide.cta2.label"></a>
                </div>
            </div>
        </template>

        {{-- Static search --}}
        <form action="{{ url('/products') }}" method="GET"
            @focusin="paused = true; stop()" @focusout="setTimeout(() => { paused = false; start() }, 3000)"
            class="flex items-center gap-2 bg-white rounded-xl shadow-xl overflow-hidden max-w-lg p-1.5">
            <i data-lucide="search" class="w-[15px] h-[15px] text-slate-400 ml-2 flex-shrink-0"></i>
            <input name="search" placeholder="Search equipment, services, suppliers..." class="flex-1 text-sm text-slate-700 outline-none placeholder:text-slate-400 py-2 bg-transparent min-w-0">
            <button type="submit" class="bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors flex-shrink-0">Search</button>
        </form>
    </div>

    {{-- Arrows --}}
    <button @click="stop(); prev(); start()" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 bg-white/15 hover:bg-white/30 backdrop-blur-sm border border-white/20 text-white p-2 rounded-full transition-all"><i data-lucide="chevron-left" class="w-[18px] h-[18px]"></i></button>
    <button @click="stop(); next(); start()" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 bg-white/15 hover:bg-white/30 backdrop-blur-sm border border-white/20 text-white p-2 rounded-full transition-all"><i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i></button>

    {{-- Dots --}}
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <template x-for="(slide, i) in slides" :key="'dot-'+i">
            <button @click="stop(); go(i); start()" class="rounded-full transition-all duration-300" :class="current === i ? 'bg-white w-5 h-2' : 'bg-white/40 w-2 h-2'"></button>
        </template>
    </div>

    {{-- Trust badges --}}
    <div class="absolute bottom-0 left-0 right-0 z-20 bg-gradient-to-t from-black/35 to-transparent pointer-events-none">
        <div class="max-w-5xl mx-auto px-6 md:px-16 py-4 flex flex-wrap gap-4 sm:gap-8">
            @foreach ([['shield', 'Verified Suppliers'], ['award', 'Certified Equipment'], ['truck', 'Nationwide Delivery'], ['clock', '24hr Response']] as [$icon, $label])
                <div class="flex items-center gap-2">
                    <i data-lucide="{{ $icon }}" class="w-[13px] h-[13px] text-accent"></i>
                    <span class="text-white/80 text-xs font-medium">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
