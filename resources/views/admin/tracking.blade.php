@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Order Tracking</h1>
        <p class="text-slate-500 text-sm">Update shipment status and tracking events</p>
    </div>
    @livewire('admin.tracking-manager')
</div>
@endsection
