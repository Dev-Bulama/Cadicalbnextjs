@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Audit Logs</h1>
        <p class="text-slate-500 text-sm">System-wide activity trail for compliance</p>
    </div>
    @livewire('admin.audit-logs-manager')
</div>
@endsection
