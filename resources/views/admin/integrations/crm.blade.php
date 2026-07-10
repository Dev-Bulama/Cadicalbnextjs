@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">CRM Integration</h1>
        <p class="text-slate-500 text-sm">Connect Cadical Solutions to Zoho CRM for customer and pipeline sync</p>
    </div>
    @livewire('admin.crm-manager')
</div>
@endsection
