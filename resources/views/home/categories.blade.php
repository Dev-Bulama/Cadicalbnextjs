@php
    $cats = [
        ['icon' => 'scan', 'name' => 'Imaging', 'sub' => 'Ultrasound, X-Ray, CT', 'href' => '/products?category=Imaging', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
        ['icon' => 'activity', 'name' => 'Diagnostics', 'sub' => 'Analyzers, POC, Reagents', 'href' => '/products?category=Diagnostics', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
        ['icon' => 'heart', 'name' => 'ICU Equipment', 'sub' => 'Ventilators, Monitors', 'href' => '/products?category=ICU', 'color' => 'bg-red-50 text-red-600 border-red-100'],
        ['icon' => 'scissors', 'name' => 'Surgical', 'sub' => 'Instruments, Electrosurgery', 'href' => '/products?category=Surgery', 'color' => 'bg-violet-50 text-violet-600 border-violet-100'],
        ['icon' => 'flask-conical', 'name' => 'Laboratory', 'sub' => 'Centrifuges, PCR, Incubators', 'href' => '/products?category=Laboratory', 'color' => 'bg-amber-50 text-amber-600 border-amber-100'],
        ['icon' => 'monitor', 'name' => 'Monitoring', 'sub' => 'Vital Signs, ECG, Oximetry', 'href' => '/products?category=Monitoring', 'color' => 'bg-cyan-50 text-cyan-600 border-cyan-100'],
        ['icon' => 'eye', 'name' => 'Dental', 'sub' => 'CBCT, Handpieces, Scalers', 'href' => '/products?category=Dental', 'color' => 'bg-pink-50 text-pink-600 border-pink-100'],
        ['icon' => 'accessibility', 'name' => 'Rehabilitation', 'sub' => 'Wheelchairs, Therapy', 'href' => '/products?category=Rehabilitation', 'color' => 'bg-orange-50 text-orange-600 border-orange-100'],
        ['icon' => 'package-2', 'name' => 'Consumables', 'sub' => 'Gloves, IV Sets, Dressings', 'href' => '/products?category=Consumables', 'color' => 'bg-slate-50 text-slate-600 border-slate-100'],
    ];
@endphp
<section class="py-16 px-4 md:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-2">Browse by Category</p>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Find the right equipment</h2>
            </div>
            <a href="{{ url('/products') }}" class="text-sm text-cadical-500 font-semibold hover:underline hidden sm:block">View all &rarr;</a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-9 gap-3">
            @foreach ($cats as $i => $cat)
                <div x-reveal="{{ $i * 0.05 }}">
                    <a href="{{ url($cat['href']) }}" class="flex flex-col items-center gap-2 p-3 md:p-4 rounded-xl border {{ $cat['color'] }} hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center group">
                        <div class="p-2.5 rounded-lg bg-white/60 group-hover:bg-white transition-colors">
                            <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-semibold leading-tight">{{ $cat['name'] }}</span>
                        <span class="text-[10px] text-slate-500 leading-tight hidden md:block">{{ $cat['sub'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-6 sm:hidden text-center">
            <a href="{{ url('/products') }}" class="text-sm text-cadical-500 font-semibold hover:underline">View all categories &rarr;</a>
        </div>
    </div>
</section>
