@php
    $slides = \App\Models\HomeSection::content('hero', ['items' => []])['items'];
@endphp
<section
    x-data="{
        slides: {{ Illuminate\Support\Js::from($slides) }},
        current: 0, paused: false, timer: null,
        start() { if (this.slides.length > 1) this.timer = setInterval(() => this.next(), 5500) },
        stop() { clearInterval(this.timer) },
        next() { if (this.slides.length) this.current = (this.current + 1) % this.slides.length },
        prev() { if (this.slides.length) this.current = (this.current - 1 + this.slides.length) % this.slides.length },
        go(i) { this.current = i },
    }"
    x-init="if (slides.length) start()"
    class="relative w-full overflow-hidden"
>
    {{-- Sliding background colour --}}
    <div class="absolute inset-0">
        <template x-for="(slide, i) in slides" :key="'bg-'+i">
            <div x-show="current === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-gradient-to-br" :class="slide.gradient"></div>
        </template>
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-16 py-16 md:py-24 grid md:grid-cols-2 gap-10 md:gap-16 items-center min-h-[600px]">
        {{-- Text column --}}
        <div class="relative">
            <template x-for="(slide, i) in slides" :key="'text-'+i">
                <div x-show="current === i" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0">
                    <span class="inline-block text-white/90 text-xs font-semibold tracking-widest uppercase border border-white/30 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full mb-5" x-text="slide.badge"></span>
                    <h1 class="text-white text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4">
                        <span x-text="slide.headline"></span>
                        <template x-if="slide.headline_accent"><span class="text-accent" x-text="' ' + slide.headline_accent"></span></template>
                    </h1>
                    <p class="text-white/75 text-sm md:text-lg leading-relaxed mb-6 max-w-xl" x-text="slide.sub"></p>
                    <div class="flex flex-wrap gap-3 mb-8">
                        <a :href="'{{ url('') }}' + slide.cta1_href" class="bg-accent hover:bg-accent-600 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-all hover:scale-105 shadow-lg" x-text="slide.cta1_label"></a>
                        <a :href="'{{ url('') }}' + slide.cta2_href" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/30 text-white px-5 py-2.5 rounded-lg font-semibold text-sm transition-all" x-text="slide.cta2_label"></a>
                    </div>
                </div>
            </template>

            {{-- Static search --}}
            <form action="{{ url('/products') }}" method="GET"
                @focusin="paused = true; stop()" @focusout="setTimeout(() => { paused = false; start() }, 3000)"
                class="flex items-center gap-2 bg-white rounded-xl shadow-xl overflow-hidden max-w-lg p-1.5 mb-8">
                <i data-lucide="search" class="w-[15px] h-[15px] text-slate-400 ml-2 flex-shrink-0"></i>
                <input name="search" placeholder="Search equipment, services, suppliers..." class="flex-1 text-sm text-slate-700 outline-none placeholder:text-slate-400 py-2 bg-transparent min-w-0">
                <button type="submit" class="bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors flex-shrink-0">Search</button>
            </form>

            {{-- Trust badges --}}
            <div class="flex flex-wrap gap-4 sm:gap-6">
                @foreach ([['shield', 'Verified Suppliers'], ['award', 'Certified Equipment'], ['truck', 'Nationwide Delivery'], ['clock', '24hr Response']] as [$icon, $label])
                    <div class="flex items-center gap-2">
                        <i data-lucide="{{ $icon }}" class="w-[13px] h-[13px] text-accent"></i>
                        <span class="text-white/80 text-xs font-medium">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Image column --}}
        <div class="relative h-72 sm:h-96 md:h-[480px]">
            <template x-for="(slide, i) in slides" :key="'img-'+i">
                <div x-show="current === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0 rounded-2xl overflow-hidden shadow-2xl">
                    <img :src="slide.image.startsWith('/') ? '{{ url('') }}' + slide.image : '{{ asset('') }}' + slide.image" :alt="slide.badge" class="w-full h-full object-cover">
                </div>
            </template>

            {{-- Arrows --}}
            <button @click="stop(); prev(); start()" x-show="slides.length > 1" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 bg-white/80 hover:bg-white text-slate-700 p-2 rounded-full shadow-lg transition-all"><i data-lucide="chevron-left" class="w-[18px] h-[18px]"></i></button>
            <button @click="stop(); next(); start()" x-show="slides.length > 1" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 bg-white/80 hover:bg-white text-slate-700 p-2 rounded-full shadow-lg transition-all"><i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i></button>

            {{-- Dots --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                <template x-for="(slide, i) in slides" :key="'dot-'+i">
                    <button @click="stop(); go(i); start()" class="rounded-full transition-all duration-300" :class="current === i ? 'bg-white w-5 h-2' : 'bg-white/50 w-2 h-2'"></button>
                </template>
            </div>
        </div>
    </div>
</section>
