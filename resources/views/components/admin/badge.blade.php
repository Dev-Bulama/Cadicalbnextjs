@props(['color' => 'slate'])
<span {{ $attributes->merge(['class' => "inline-block text-[11px] font-semibold px-2.5 py-1 rounded-full bg-{$color}-100 text-{$color}-700"]) }}>
    {{ $slot }}
</span>
