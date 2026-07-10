@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Clinician Management</h1>
        <p class="text-slate-500 text-sm">Manage and verify healthcare professionals</p>
    </div>
    @livewire('admin.clinicians-manager')
</div>
@endsection
