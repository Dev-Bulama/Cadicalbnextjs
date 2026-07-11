@php
    $portalName = 'Cadical Clinician Portal';
    $portalSubtitle = 'Professional profile';
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/clinician/dashboard'],
        ['name' => 'Opportunities', 'href' => '/clinician/opportunities'],
        ['name' => 'Profile', 'href' => '/clinician/profile'],
    ];
@endphp
@extends('layouts.portal')
@section('content')
<div class="p-6 sm:p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Professional Profile</h1>
        <p class="text-slate-500 text-sm">Manage your professional information and availability</p>
    </div>
    @livewire('clinician.profile-manager')
</div>
@endsection
