@php
    $pillars = [
        ['icon' => 'shield-check', 'title' => 'Certified Products Only', 'desc' => 'Every product we carry is NAFDAC-registered and manufacturer-verified. No counterfeits, no shortcuts.', 'color' => 'text-blue-600 bg-blue-50'],
        ['icon' => 'zap', 'title' => 'Fast, Dependable Delivery', 'desc' => "Lagos same-day, nationwide within 48–72 hours. Your operations don't stop — we make sure of it.", 'color' => 'text-amber-600 bg-amber-50'],
        ['icon' => 'users', 'title' => 'Relationship-Driven', 'desc' => 'A dedicated account manager, not a ticket system. We pick up the phone and we follow through.', 'color' => 'text-emerald-600 bg-emerald-50'],
        ['icon' => 'award', 'title' => 'Healthcare Specialists', 'desc' => 'Our team has deep domain knowledge — biomedical engineers, procurement experts, and logistics specialists.', 'color' => 'text-violet-600 bg-violet-50'],
    ];
@endphp
<section id="why" class="py-20 px-4 md:px-8 bg-white">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">
        <div x-reveal class="relative">
            <div class="relative rounded-2xl overflow-hidden aspect-[4/3] shadow-2xl">
                <img src="{{ asset('test.jpeg') }}" alt="Why Cadical" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-cadical-700/60 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 bg-white/95 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-[18px] h-[18px] text-cadical-500"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">Trusted by 50+ healthcare facilities</div>
                            <div class="text-xs text-slate-500">Hospitals, clinics and labs across Nigeria</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div x-reveal>
                <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">Why Cadical</p>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3 leading-tight">
                    Reliable supply is not a luxury.<br>
                    <span class="text-cadical-500">It's the baseline.</span>
                </h2>
                <p class="text-slate-500 mb-8 leading-relaxed">
                    Most healthcare supply chains in Nigeria fail at the last mile — late deliveries, wrong products, no follow-up. We built Cadical to be the partner healthcare providers actually deserve.
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
