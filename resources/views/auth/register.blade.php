@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white flex items-center justify-center p-4 pt-20">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Cadical" class="w-10 h-10 rounded-xl">
                <div class="text-left">
                    <div class="text-cadical-500 font-bold text-base">Cadical Solutions</div>
                    <div class="text-slate-400 text-xs">Right Supply. Right Time.</div>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Create your account</h1>
            <p class="text-slate-500 text-sm mt-1">Join Nigeria's healthcare supply platform</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
            @php $inputClass = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500 transition-colors'; @endphp
            <form method="POST" action="{{ url('/auth/register') }}" class="space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="John" required class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required class="{{ $inputClass }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required class="{{ $inputClass }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required class="{{ $inputClass }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required class="{{ $inputClass }}">
                </div>

                <button type="submit"
                    class="w-full bg-cadical-500 hover:bg-cadical-700 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors">
                    Create Account
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Already have an account?
                <a href="{{ url('/auth/login') }}" class="text-cadical-500 font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
