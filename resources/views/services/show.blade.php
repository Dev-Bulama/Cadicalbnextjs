@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50 pt-24 pb-16 px-4 flex flex-col items-center">
    <div class="w-full max-w-3xl bg-white rounded-2xl border border-slate-100 p-8">
        <h1 class="text-4xl font-bold flex items-center gap-3 mb-6 text-slate-900">
            <span>{{ $service['icon'] }}</span> {{ $service['title'] }}
        </h1>
        <p class="text-slate-600 leading-relaxed mb-6">{{ $service['intro'] }}</p>
        <h2 class="text-xl font-semibold text-slate-900 mb-3">Our Services</h2>
        <ul class="list-disc list-inside space-y-2 text-slate-700 mb-8">
            @foreach ($service['items'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
        <a href="{{ url('/booking') }}" class="inline-flex items-center gap-2 bg-cadical-500 hover:bg-cadical-700 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-colors">Book This Service</a>
    </div>
</div>
@endsection
