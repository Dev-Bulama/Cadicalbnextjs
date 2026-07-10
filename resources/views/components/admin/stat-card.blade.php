@props(['icon', 'label', 'value', 'color' => 'cadical'])
<div class="bg-white rounded-2xl border border-slate-100 p-5">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 bg-{{ $color }}-500/10 text-{{ $color }}-500">
        <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
    </div>
    <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
    <p class="text-xs text-slate-400 mt-0.5">{{ $label }}</p>
</div>
