@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50">
    <section class="py-24 px-6 pt-32 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-slate-900">Get in Touch With Cadical</h1>
            <p class="text-slate-500 text-lg">Whether you need reliable health products or qualified medical professionals, our team is ready to assist you.</p>
        </div>
    </section>

    <section class="pb-24 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12">
            @livewire('contact-form')

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-start gap-4">
                    <i data-lucide="mail" class="w-6 h-6 mt-1 text-cadical-500"></i>
                    <div>
                        <h3 class="font-semibold text-slate-900">Email</h3>
                        <p class="text-slate-500 text-sm">support@cadical.com</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 flex items-start gap-4">
                    <i data-lucide="phone" class="w-6 h-6 mt-1 text-cadical-500"></i>
                    <div>
                        <h3 class="font-semibold text-slate-900">Phone</h3>
                        <p class="text-slate-500 text-sm">+234 707 617 5550</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-cadical-700 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">Let's Improve Healthcare Together</h2>
            <p class="mb-8 text-white/80">Partner with Cadical for trusted healthcare solutions tailored to your needs.</p>
            <a href="{{ url('/about') }}" class="inline-block bg-white text-cadical-700 px-8 py-3.5 rounded-2xl font-semibold hover:bg-slate-100 transition-colors">Learn More About Us</a>
        </div>
    </section>
</div>
@endsection
