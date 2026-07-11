@php
    $portalName = 'Cadical Supplier Portal';
    $portalSubtitle = 'Company profile';
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/supplier/dashboard'],
        ['name' => 'Products', 'href' => '/supplier/products'],
        ['name' => 'Orders', 'href' => '/supplier/orders'],
        ['name' => 'Profile', 'href' => '/supplier/profile'],
    ];
@endphp
@extends('layouts.portal')
@section('content')
<div class="p-6 sm:p-8 max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Company Profile</h1>
        <p class="text-slate-500 text-sm">Manage how your business appears on the platform</p>
    </div>
    @livewire('supplier.profile-manager')
</div>
@endsection
