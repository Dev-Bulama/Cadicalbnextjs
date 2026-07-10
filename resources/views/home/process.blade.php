@php
    $steps = [
        ['n' => '01', 'title' => 'Browse or Request', 'desc' => 'Explore the MediStore, use the institutional portal, or submit a custom equipment request.'],
        ['n' => '02', 'title' => 'Verify & Confirm', 'desc' => 'Our compliance team confirms product availability, certification, and pricing for your order.'],
        ['n' => '03', 'title' => 'Fast Dispatch', 'desc' => 'We pick, pack, and dispatch same-day (Lagos) or next-day (nationwide) with tracking.'],
        ['n' => '04', 'title' => 'Track & Receive', 'desc' => 'Monitor your shipment in real-time. Receive delivery confirmation and invoice automatically.'],
    ];
@endphp
<section class="py-20 px-4 md:px-8 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">How It Works</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3">Simple from start to finish.</h2>
            <p class="text-slate-500">Whether you're ordering supplies or booking a service — the process is seamless.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            <div class="absolute top-6 left-[12.5%] right-[12.5%] h-px bg-slate-200 hidden lg:block"></div>
            @foreach ($steps as $i => $s)
                <div x-reveal="{{ $i * 0.12 }}" class="relative">
                    <div class="w-12 h-12 rounded-full border-2 border-cadical-500 bg-white flex items-center justify-center text-cadical-500 font-bold font-mono text-sm mb-4 relative z-10">{{ $s['n'] }}</div>
                    <h4 class="font-bold text-slate-900 mb-1.5">{{ $s['title'] }}</h4>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
