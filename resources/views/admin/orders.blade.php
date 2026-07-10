@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Orders</h1>
        <p class="text-slate-500 text-sm">Manage marketplace orders and shipment status</p>
    </div>
    @livewire('admin.orders-manager')
</div>
@endsection
