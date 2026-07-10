@php
    $services = [
        ['icon' => 'wrench', 'title' => 'Equipment Repair', 'desc' => 'Fast on-site diagnosis and repair for all major brands — Philips, GE, Siemens, Mindray and more.', 'tags' => ['Imaging', 'ICU', 'Diagnostics'], 'color' => 'text-blue-600 bg-blue-50', 'cta' => 'Book Repair'],
        ['icon' => 'settings', 'title' => 'Preventive Maintenance', 'desc' => 'Scheduled quarterly or annual maintenance contracts to keep your equipment running at peak performance.', 'tags' => ['Annual Plans', 'Quarterly', 'Reports'], 'color' => 'text-emerald-600 bg-emerald-50', 'cta' => 'Get a Plan'],
        ['icon' => 'gauge', 'title' => 'Calibration', 'desc' => 'Certified calibration services for diagnostic and measurement equipment to regulatory standards.', 'tags' => ['ISO 9001', 'Certificates', 'Auditable'], 'color' => 'text-violet-600 bg-violet-50', 'cta' => 'Book Calibration'],
        ['icon' => 'phone', 'title' => 'Supply Consultation', 'desc' => 'Expert procurement advice for hospitals, clinics and institutions on sourcing, budgeting, and contracts.', 'tags' => ['Free Consult', 'Procurement', 'Budget'], 'color' => 'text-amber-600 bg-amber-50', 'cta' => 'Talk to Us'],
    ];
@endphp
<section id="services" class="py-20 px-4 md:px-8 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">Medical Services</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3">We don't just supply — we support.</h2>
            <p class="text-slate-500 max-w-xl mx-auto">From emergency repair to scheduled maintenance, our certified engineers keep your equipment operational.</p>
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
                Book Any Service <i data-lucide="arrow-right" class="w-[15px] h-[15px]"></i>
            </a>
        </div>
    </div>
</section>
