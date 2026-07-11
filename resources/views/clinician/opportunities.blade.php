@php
    $portalName = 'Cadical Clinician Portal';
    $portalSubtitle = 'Job opportunities';
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/clinician/dashboard'],
        ['name' => 'Opportunities', 'href' => '/clinician/opportunities'],
        ['name' => 'Profile', 'href' => '/clinician/profile'],
    ];
@endphp
@extends('layouts.portal')
@section('content')
<div class="p-6 sm:p-8 max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Job Opportunities</h1>
        <p class="text-slate-500 text-sm mt-1">Browse positions matching your specialization</p>
    </div>

    <form method="GET" class="relative max-w-sm">
        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search opportunities…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
    </form>

    <div class="space-y-4">
        @forelse ($opportunities as $opp)
            <div class="bg-white rounded-2xl border border-slate-100 p-5 hover:shadow-sm transition-shadow">
                <div class="flex justify-between items-start gap-4 mb-3">
                    <div>
                        <h3 class="font-semibold text-slate-900">{{ $opp['title'] }}</h3>
                        <p class="text-sm text-slate-400 mt-0.5">{{ $opp['company'] }}</p>
                    </div>
                    <x-admin.badge color="cadical">{{ $opp['type'] }}</x-admin.badge>
                </div>
                <p class="text-slate-700 text-sm mb-4">{{ $opp['description'] }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                    <div><p class="font-medium text-slate-900">Location</p><p class="text-slate-400">{{ $opp['location'] }}</p></div>
                    <div><p class="font-medium text-slate-900">Specialization</p><p class="text-slate-400">{{ $opp['specialization'] }}</p></div>
                    <div><p class="font-medium text-slate-900">Experience</p><p class="text-slate-400">{{ $opp['yearsRequired'] }}+ years</p></div>
                    <div><p class="font-medium text-slate-900">Posted</p><p class="text-slate-400">{{ $opp['postedDate'] }}</p></div>
                </div>
                <button type="button" class="w-full py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Apply Now</button>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">No opportunities found</div>
        @endforelse
    </div>
</div>
@endsection
