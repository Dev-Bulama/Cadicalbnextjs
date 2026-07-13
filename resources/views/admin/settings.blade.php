@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Settings</h1>
        <p class="text-slate-500 text-sm">Configure payment gateway keys and outgoing mail (SMTP) without touching the server.</p>
    </div>
    @livewire('admin.settings-manager')
</div>
@endsection
