@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50 pt-24 pb-16 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl md:text-5xl font-bold text-center text-slate-900 mb-12">Our Services</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($services as $slug => $s)
                <a href="{{ url('/services/'.$slug) }}" class="group bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl">{{ $s['icon'] }}</span>
                        <h2 class="text-xl font-bold text-slate-900 group-hover:text-cadical-500 transition-colors">{{ $s['title'] }}</h2>
                    </div>
                    <p class="text-sm text-slate-500">{{ $s['summary'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
