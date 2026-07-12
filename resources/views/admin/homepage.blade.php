@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Homepage Content</h1>
        <p class="text-slate-500 text-sm">Edit the text, images and lists shown on the public homepage</p>
    </div>
    @livewire('admin.home-content-manager')
</div>
@endsection
