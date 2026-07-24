@php
    $section = \App\Models\HomeSection::content('privacy', ['meta' => [], 'items' => []]);
    $m = $section['meta'];
    $clauses = $section['items'];
@endphp
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50 py-24 px-6 pt-32">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold mb-3 text-slate-900">{{ $m['heading'] ?? 'Privacy Policy' }}</h1>
        <p class="text-slate-400 mb-10">{{ $m['effective_date'] ?? '' }}</p>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 space-y-8">
            @foreach ($clauses as $clause)
                @if (!$loop->first)<hr class="border-slate-100">@endif
                <section class="space-y-3">
                    <h2 class="text-2xl font-semibold text-slate-900">{{ $clause['title'] ?? '' }}</h2>
                    <p class="text-slate-500">{{ $clause['body'] ?? '' }}</p>
                    @if (!empty($clause['bullets']))
                        <ul class="list-disc pl-6 text-slate-500 space-y-1.5">
                            @foreach ($clause['bullets'] as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach
        </div>
    </div>
</div>
@endsection
