@php
    $stages = [
        ['icon' => 'check-circle-2', 'label' => 'Order Placed', 'desc' => 'Order confirmed & payment processed', 'done' => true, 'active' => false],
        ['icon' => 'package', 'label' => 'Processing', 'desc' => 'Equipment verified and packed', 'done' => true, 'active' => false],
        ['icon' => 'truck', 'label' => 'Dispatched', 'desc' => 'In transit via certified carrier', 'done' => true, 'active' => true],
        ['icon' => 'map-pin', 'label' => 'Out for Delivery', 'desc' => 'Near your location', 'done' => false, 'active' => false],
        ['icon' => 'check-circle-2', 'label' => 'Delivered', 'desc' => 'Signed & received by your facility', 'done' => false, 'active' => false],
    ];
@endphp
<section class="py-20 px-4 md:px-8 bg-white">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div x-reveal>
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">Real-Time Tracking</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-4 leading-tight">Always know where your order is.</h2>
            <p class="text-slate-500 leading-relaxed mb-6">From the moment you place an order to the point of delivery, you have full visibility into every step of your shipment. No calls required.</p>
            <ul class="space-y-2 mb-8 text-sm text-slate-600">
                @foreach (['Live status updates via SMS & email', 'Estimated delivery window', 'Delivery confirmation & proof'] as $t)
                    <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0"></i> {{ $t }}</li>
                @endforeach
            </ul>
            <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 bg-cadical-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-cadical-700 transition-colors">Track Your Order</a>
        </div>

        <div x-reveal="0.1" class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200">
                <div>
                    <div class="text-xs text-slate-400 mb-0.5">Order</div>
                    <div class="font-bold text-slate-900 font-mono text-sm">CAD-M3Q9Z-2026</div>
                </div>
                <span class="bg-amber-100 text-amber-700 text-[11px] font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                    <i data-lucide="clock" class="w-2.5 h-2.5"></i> In Transit
                </span>
            </div>

            <div class="space-y-0">
                @foreach ($stages as $i => $stage)
                    <div class="flex gap-4 relative">
                        @if ($i < count($stages) - 1)
                            <div class="absolute left-[18px] top-8 bottom-0 w-0.5 {{ $stage['done'] ? 'bg-cadical-500' : 'bg-slate-200' }}" style="height: calc(100% - 8px)"></div>
                        @endif
                        <div class="relative z-10 w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 {{ $stage['active'] ? 'bg-cadical-500 ring-4 ring-blue-100' : ($stage['done'] ? 'bg-cadical-500' : 'bg-slate-200') }}">
                            <i data-lucide="{{ $stage['icon'] }}" class="w-4 h-4 {{ $stage['done'] || $stage['active'] ? 'text-white' : 'text-slate-400' }}"></i>
                        </div>
                        <div class="pb-5">
                            <div class="text-sm font-semibold {{ $stage['active'] ? 'text-cadical-500' : ($stage['done'] ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $stage['label'] }}
                                @if ($stage['active'])
                                    <span class="ml-2 text-[10px] bg-blue-100 text-cadical-500 px-2 py-0.5 rounded-full font-medium">Now</span>
                                @endif
                            </div>
                            <div class="text-xs mt-0.5 {{ $stage['done'] || $stage['active'] ? 'text-slate-500' : 'text-slate-300' }}">{{ $stage['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
