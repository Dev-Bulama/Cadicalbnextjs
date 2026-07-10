@extends('layouts.app')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-blue-50 to-white px-4">
    <div class="max-w-2xl text-center py-24">
        <img src="{{ asset('images/logo.png') }}" alt="Cadical" class="w-16 h-16 rounded-2xl mx-auto mb-6">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-3">Cadical Solutions</h1>
        <p class="text-slate-500 text-lg mb-2">Right Supply. Right Time.</p>
        <p class="text-slate-400 text-sm max-w-md mx-auto">
            The Laravel rebuild is under active development. This is Phase 1 (foundation: database, auth,
            base layout). The full storefront, admin console, and portals land in the next phases.
        </p>
        <div class="flex items-center justify-center gap-3 mt-8">
            <a href="{{ url('/auth/login') }}" class="bg-cadical-500 hover:bg-cadical-700 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-colors">Sign In</a>
            <a href="{{ url('/auth/register') }}" class="border border-slate-200 text-slate-700 font-semibold px-6 py-3 rounded-xl text-sm transition-colors">Create Account</a>
        </div>
    </div>
</div>
@endsection
