@php
    $portalName = 'Cadical Clinician Portal';
    $portalSubtitle = $clinician->specialization;
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/clinician/dashboard'],
        ['name' => 'Opportunities', 'href' => '/clinician/opportunities'],
        ['name' => 'Profile', 'href' => '/clinician/profile'],
    ];
@endphp
@extends('layouts.portal')
@section('content')
<div class="p-6 sm:p-8 max-w-5xl mx-auto space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Welcome to your professional dashboard</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="{{ $clinician->verified ? 'check-circle-2' : 'alert-circle' }}" class="w-4 h-4 {{ $clinician->verified ? 'text-emerald-600' : 'text-amber-600' }}"></i>
                <p class="text-sm font-medium text-slate-500">Verification Status</p>
            </div>
            <p class="text-2xl font-bold text-slate-900 mb-1">{{ $clinician->verified ? 'Verified' : 'Pending' }}</p>
            <p class="text-xs text-slate-400">{{ $clinician->verified ? 'You are verified and can accept opportunities' : 'Awaiting admin verification' }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <p class="text-sm font-medium text-slate-500 mb-2">Availability</p>
            <p class="text-2xl font-bold text-slate-900 mb-1">{{ $clinician->is_available ? 'Available' : 'Unavailable' }}</p>
            <p class="text-xs text-slate-400">Clients can see your profile</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="briefcase" class="w-4 h-4 text-slate-500"></i>
                <p class="text-sm font-medium text-slate-500">Opportunities</p>
            </div>
            <p class="text-2xl font-bold text-slate-900 mb-1">{{ count($opportunities) }}</p>
            <p class="text-xs text-slate-400">Available positions matching your profile</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h3 class="font-semibold text-slate-900 mb-4">Recent Opportunities</h3>
        @if (empty($opportunities))
            <p class="text-slate-400 text-center py-8">No opportunities available at the moment</p>
        @else
            <div class="space-y-4">
                @foreach (array_slice($opportunities, 0, 5) as $opp)
                    <div class="flex justify-between items-start border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                        <div>
                            <h4 class="font-medium text-slate-900">{{ $opp['title'] }}</h4>
                            <p class="text-sm text-slate-500">{{ $opp['description'] }}</p>
                        </div>
                        <x-admin.badge color="cadical">{{ $opp['type'] }}</x-admin.badge>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
