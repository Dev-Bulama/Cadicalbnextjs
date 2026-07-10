@php
    $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
@endphp
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10">
    <h2 class="text-2xl font-semibold mb-6 text-slate-900">Send Us a Message</h2>

    @if ($sent)
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
            <i data-lucide="check-circle" class="w-10 h-10 text-emerald-500 mx-auto mb-3"></i>
            <p class="font-semibold text-emerald-700 mb-1">Message sent!</p>
            <p class="text-sm text-emerald-600">We'll get back to you shortly.</p>
            <button wire:click="$set('sent', false)" class="mt-4 text-sm text-cadical-500 font-semibold hover:underline">Send another message</button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name</label><input wire:model="name" placeholder="John Doe" class="{{ $inputClass }}"><x-input-error field="name" /></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label><input type="email" wire:model="email" placeholder="you@example.com" class="{{ $inputClass }}"><x-input-error field="email" /></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Subject</label><input wire:model="subject" placeholder="How can we help?" class="{{ $inputClass }}"><x-input-error field="subject" /></div>
            <div><label class="block text-sm font-medium text-slate-700 mb-1.5">Message</label><textarea wire:model="message" rows="5" placeholder="Write your message here..." class="{{ $inputClass }}"></textarea><x-input-error field="message" /></div>
            <button type="submit" wire:loading.attr="disabled" class="w-full bg-cadical-500 hover:bg-cadical-700 disabled:opacity-70 text-white font-semibold py-3 rounded-2xl text-sm transition-colors">
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending…</span>
            </button>
        </form>
    @endif
</div>
