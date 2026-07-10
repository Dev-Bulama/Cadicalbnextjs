@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-slate-50">
    <section class="py-28 px-6 pt-32">
        <div class="max-w-6xl mx-auto text-center">
            <span class="inline-block bg-cadical-500/10 text-cadical-500 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">About Cadical</span>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-slate-900">Delivering Healthcare Solutions You Can Rely On</h1>
            <p class="text-slate-500 text-lg md:text-xl max-w-3xl mx-auto">
                Cadical bridges the gap between high-quality health products and skilled medical professionals. We empower healthcare facilities and organizations with reliable solutions that improve patient care and operational efficiency.
            </p>
            <div class="mt-10 flex justify-center gap-4 flex-wrap">
                <a href="{{ url('/contact') }}" class="bg-cadical-500 hover:bg-cadical-700 text-white px-8 py-3.5 rounded-2xl font-semibold transition-colors">Partner With Us</a>
                <a href="{{ url('/services') }}" class="border border-slate-200 text-slate-700 px-8 py-3.5 rounded-2xl font-semibold hover:bg-white transition-colors">Explore Services</a>
            </div>
        </div>
    </section>

    <section class="py-20 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900">What We Do</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Comprehensive healthcare solutions tailored for modern medical institutions and organizations.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10">
                    <span class="text-4xl mb-4 block">🩺</span>
                    <h3 class="text-2xl font-semibold mb-3 text-slate-900">Health Product Sales</h3>
                    <p class="text-slate-500 mb-5">We supply hospitals, clinics, and pharmacies with safe, compliant, and affordable medical products sourced from trusted partners.</p>
                    <ul class="space-y-2.5">
                        @foreach (['Regulatory compliant products', 'Reliable supply chain', 'Affordable pricing structure'] as $item)
                            <li class="flex items-center gap-3 text-sm text-slate-700"><i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10">
                    <span class="text-4xl mb-4 block">👥</span>
                    <h3 class="text-2xl font-semibold mb-3 text-slate-900">Medical Staff Outsourcing</h3>
                    <p class="text-slate-500 mb-5">We connect healthcare facilities with verified and credentialed professionals for short-term, long-term, and contract-based roles.</p>
                    <ul class="space-y-2.5">
                        @foreach (['Doctors & Specialists', 'Nurses & Caregivers', 'Pharmacists & Lab Scientists'] as $item)
                            <li class="flex items-center gap-3 text-sm text-slate-700"><i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-white">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            @foreach ([
                ['shield-check', 'Our Mission', 'To deliver accessible healthcare solutions through quality products and dependable medical staffing services.'],
                ['heart-pulse', 'Our Vision', 'To become a leading healthcare solutions provider recognized for excellence, integrity, and innovation.'],
                ['stethoscope', 'Our Core Values', 'Integrity, Quality, Reliability, and Care guide everything we do and every partnership we build.'],
            ] as [$icon, $title, $text])
                <div class="bg-slate-50 rounded-2xl p-8 text-center border border-slate-100">
                    <i data-lucide="{{ $icon }}" class="w-10 h-10 mx-auto mb-4 text-cadical-500"></i>
                    <h3 class="text-xl font-semibold mb-3 text-slate-900">{{ $title }}</h3>
                    <p class="text-slate-500 text-sm">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-28 px-6 bg-cadical-700 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Partner With Cadical?</h2>
            <p class="mb-10 text-lg text-white/80">Let's provide your organization with trusted health products and skilled medical professionals.</p>
            <a href="{{ url('/contact') }}" class="inline-block bg-accent hover:bg-accent-600 text-white px-10 py-3.5 rounded-2xl font-semibold transition-colors">Get Started Today</a>
        </div>
    </section>
</div>
@endsection
