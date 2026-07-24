@extends('layouts.app')
@section('content')
@php
    $siteLogo = \App\Models\HomeSection::mediaUrl(config('site.logo')) ?: asset('images/logo.png');
    $isCustomLogo = filled(config('site.logo'));
    $showSiteName = config('site.show_name') !== '0';
@endphp
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 mb-6">
                <img src="{{ $siteLogo }}" alt="Cadical" class="w-10 h-10 {{ $isCustomLogo ? 'object-contain' : 'rounded-xl' }}">
                @if ($showSiteName)
                    <div class="text-left">
                        <div class="text-cadical-500 font-bold text-base">Cadical Solutions</div>
                        <div class="text-slate-400 text-xs">Right Supply. Right Time.</div>
                    </div>
                @endif
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back</h1>
            <p class="text-slate-500 text-sm mt-1">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            <form method="POST" action="{{ url('/auth/login') }}" class="space-y-5">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500 transition-colors">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <a href="#" class="text-xs text-cadical-500 hover:underline">Forgot password?</a>
                    </div>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500 transition-colors">
                </div>

                <input type="hidden" name="remember" value="1">

                <button type="submit"
                    class="w-full bg-cadical-500 hover:bg-cadical-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Don't have an account?
                <a href="{{ url('/auth/register') }}" class="text-cadical-500 font-semibold hover:underline">Create account</a>
            </p>
        </div>

        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-slate-600">
            <p class="font-semibold text-slate-700 mb-2">Demo credentials</p>
            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                <span class="text-slate-500">Admin:</span><span class="font-mono">admin@cadical.com</span>
                <span class="text-slate-500">Technician:</span><span class="font-mono">technician@cadical.com</span>
                <span class="text-slate-500">Supplier:</span><span class="font-mono">supplier@cadical.com</span>
                <span class="text-slate-500">Password:</span><span class="font-mono font-bold text-cadical-500">Cadical@2026</span>
            </div>
        </div>
    </div>
</div>
@endsection
