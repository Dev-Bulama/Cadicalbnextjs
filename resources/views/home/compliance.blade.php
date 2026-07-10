@php
    $items = [
        ['tag' => 'CAC', 'title' => 'Corporate Affairs Commission', 'detail' => 'RC 8969474 — legally registered entity'],
        ['tag' => 'NAFDAC', 'title' => 'Drug & Device Compliance', 'detail' => 'Per-SKU product registration'],
        ['tag' => 'NDPA', 'title' => 'Data Protection', 'detail' => 'privacy@cadical.com'],
        ['tag' => 'ISO', 'title' => 'Quality Standards', 'detail' => 'ISO 13485 aligned processes'],
    ];
@endphp
<section id="compliance" class="py-16 px-4 md:px-8 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="shield-check" class="w-[18px] h-[18px] text-cadical-500"></i>
                    <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest">Compliance</p>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Regulated. Registered. <span class="text-cadical-500 italic">Auditable.</span></h2>
            </div>
            <p class="text-slate-500 text-sm max-w-sm">Every product and process at Cadical is backed by regulatory compliance.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($items as $i => $c)
                <div x-reveal="{{ $i * 0.08 }}" class="border border-slate-100 p-5 rounded-xl bg-slate-50 hover:shadow-sm transition-shadow">
                    <span class="inline-block text-[10px] font-bold bg-blue-100 text-cadical-500 px-2.5 py-1 rounded-full mb-3 uppercase tracking-widest">{{ $c['tag'] }}</span>
                    <h4 class="font-semibold text-slate-900 text-sm mb-1">{{ $c['title'] }}</h4>
                    <p class="text-xs text-slate-500">{{ $c['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
