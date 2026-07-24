@php
    $m = \App\Models\HomeSection::content('about', ['meta' => []])['meta'];
    $services = \App\Models\HomeSection::content('about_services', ['items' => []])['items'];
    $values = \App\Models\HomeSection::content('about_values', ['items' => []])['items'];
@endphp
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50">
    <section class="py-28 px-6 pt-32">
        <div class="max-w-6xl mx-auto text-center">
            <span class="inline-block bg-cadical-500/10 text-cadical-500 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">{{ $m['hero_badge'] ?? '' }}</span>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-slate-900">{{ $m['hero_heading'] ?? '' }}</h1>
            <p class="text-slate-500 text-lg md:text-xl max-w-3xl mx-auto">
                {{ $m['hero_paragraph'] ?? '' }}
            </p>
            <div class="mt-10 flex justify-center gap-4 flex-wrap">
                <a href="{{ url($m['hero_cta1_href'] ?? '/contact') }}" class="bg-cadical-500 hover:bg-cadical-700 text-white px-8 py-3.5 rounded-2xl font-semibold transition-colors">{{ $m['hero_cta1_label'] ?? 'Partner With Us' }}</a>
                <a href="{{ url($m['hero_cta2_href'] ?? '/services') }}" class="border border-slate-200 text-slate-700 px-8 py-3.5 rounded-2xl font-semibold hover:bg-white transition-colors">{{ $m['hero_cta2_label'] ?? 'Explore Services' }}</a>
            </div>
        </div>
    </section>

    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900">{{ $m['what_heading'] ?? 'What We Do' }}</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">{{ $m['what_sub'] ?? '' }}</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                @foreach ($services as $service)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10">
                        <span class="text-4xl mb-4 block">{{ $service['icon'] ?? '' }}</span>
                        <h3 class="text-2xl font-semibold mb-3 text-slate-900">{{ $service['title'] ?? '' }}</h3>
                        <p class="text-slate-500 mb-5">{{ $service['desc'] ?? '' }}</p>
                        <ul class="space-y-2.5">
                            @foreach ($service['bullets'] ?? [] as $item)
                                <li class="flex items-center gap-3 text-sm text-slate-700"><i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-white">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            @foreach ($values as $value)
                <div class="bg-slate-50 rounded-2xl p-8 text-center border border-slate-100">
                    <i data-lucide="{{ $value['icon'] ?? 'shield-check' }}" class="w-10 h-10 mx-auto mb-4 text-cadical-500"></i>
                    <h3 class="text-xl font-semibold mb-3 text-slate-900">{{ $value['title'] ?? '' }}</h3>
                    <p class="text-slate-500 text-sm">{{ $value['text'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-28 px-6 bg-cadical-700 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">{{ $m['cta_heading'] ?? '' }}</h2>
            <p class="mb-10 text-lg text-white/80">{{ $m['cta_paragraph'] ?? '' }}</p>
            <a href="{{ url($m['cta_button_href'] ?? '/contact') }}" class="inline-block bg-accent hover:bg-accent-600 text-white px-10 py-3.5 rounded-2xl font-semibold transition-colors">{{ $m['cta_button_label'] ?? 'Get Started Today' }}</a>
        </div>
    </section>
</div>
@endsection
