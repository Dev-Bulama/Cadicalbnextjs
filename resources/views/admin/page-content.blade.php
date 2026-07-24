@extends('layouts.admin')
@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Page Content</h1>
        <p class="text-slate-500 text-sm">Edit the text shown on About, Contact, Terms, Privacy, Referral and Booking pages</p>
    </div>
    @livewire('admin.page-content-manager')
</div>
@endsection
