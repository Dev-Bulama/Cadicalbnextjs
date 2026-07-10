@php
    $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
    $labelClass = 'block text-sm font-medium text-slate-700 mb-1.5';
@endphp
<div class="min-h-screen bg-slate-50 pt-16">
    @if ($submitted)
        <div class="min-h-[70vh] flex items-center justify-center p-6">
            <div class="max-w-md w-full text-center bg-white rounded-2xl border border-slate-200 pt-10 pb-8 px-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">RFQ Submitted Successfully</h2>
                <p class="text-slate-500 text-sm mb-4">Your request for quotation has been received. Approved suppliers will submit bids within 48–72 hours.</p>
                <div class="p-3 bg-slate-50 rounded-lg mb-6">
                    <p class="text-xs text-slate-400">Your RFQ Reference</p>
                    <p class="font-mono font-bold text-lg text-slate-900">{{ $rfqCode }}</p>
                </div>
                <p class="text-xs text-slate-400">Save your reference code. Our team will contact you at the email provided to share supplier bids.</p>
            </div>
        </div>
    @else
        <div class="border-b border-slate-200 bg-white px-4 md:px-8 py-5">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-cadical-500 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-text" class="w-[18px] h-[18px] text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-slate-900">Request for Quotation</h1>
                        <p class="text-xs text-slate-500">Submit your medical equipment procurement requirements</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 md:px-8 py-10">
            <form wire:submit="submit" class="space-y-6">
                {{-- Contact --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-4"><i data-lucide="building-2" class="w-4 h-4 text-cadical-500"></i> Organization & Contact</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="{{ $labelClass }}">Full Name *</label><input wire:model="contactName" placeholder="Dr. Amara Osei" class="{{ $inputClass }}"><x-input-error field="contactName" /></div>
                        <div><label class="{{ $labelClass }}">Organization / Hospital</label><input wire:model="organization" placeholder="Lagos General Hospital" class="{{ $inputClass }}"></div>
                        <div><label class="{{ $labelClass }}">Email Address *</label><input type="email" wire:model="contactEmail" placeholder="procurement@hospital.ng" class="{{ $inputClass }}"><x-input-error field="contactEmail" /></div>
                        <div><label class="{{ $labelClass }}">Phone Number *</label><input wire:model="contactPhone" placeholder="+234 801 234 5678" class="{{ $inputClass }}"><x-input-error field="contactPhone" /></div>
                    </div>
                </div>

                {{-- Requirements --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2"><i data-lucide="package" class="w-4 h-4 text-cadical-500"></i> Equipment Requirements</h3>
                    <div><label class="{{ $labelClass }}">RFQ Title *</label><input wire:model="title" placeholder="e.g. ICU Ventilators for 20-bed ward" class="{{ $inputClass }}"><x-input-error field="title" /></div>
                    <div><label class="{{ $labelClass }}">Description *</label><textarea wire:model="description" rows="3" placeholder="Describe what you need, intended use, environment, etc." class="{{ $inputClass }}"></textarea><x-input-error field="description" /></div>

                    <div>
                        <label class="{{ $labelClass }}">Equipment Categories *</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach (\App\Livewire\RfqForm::CATEGORIES as $cat)
                                <button type="button" wire:click="toggleCategory('{{ $cat }}')"
                                    class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ in_array($cat, $category) ? 'bg-cadical-500 text-white border-cadical-500' : 'border-slate-200 text-slate-500 hover:border-cadical-500/50' }}">
                                    {{ $cat }}
                                </button>
                            @endforeach
                        </div>
                        @if (count($category) > 0)<p class="text-xs text-slate-400 mt-2">{{ count($category) }} selected</p>@endif
                    </div>

                    <div><label class="{{ $labelClass }}">Technical Specifications</label><textarea wire:model="specifications" rows="3" placeholder="List technical specs, certifications required, brand preferences, etc." class="{{ $inputClass }}"></textarea></div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div><label class="{{ $labelClass }}">Quantity Required *</label><input type="number" min="1" wire:model="quantity" placeholder="10" class="{{ $inputClass }}"><x-input-error field="quantity" /></div>
                        <div><label class="{{ $labelClass }}">Target Budget</label><input type="number" wire:model="targetBudget" placeholder="500000" class="{{ $inputClass }}"></div>
                        <div>
                            <label class="{{ $labelClass }}">Currency</label>
                            <select wire:model="currency" class="{{ $inputClass }}">
                                <option value="NGN">NGN (&#8358;)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (&euro;)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Delivery --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-4"><i data-lucide="calendar" class="w-4 h-4 text-cadical-500"></i> Delivery & Timeline</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="{{ $labelClass }}">Required Delivery Date</label><input type="date" wire:model="deliveryDate" class="{{ $inputClass }}"></div>
                        <div><label class="{{ $labelClass }}">Bid Closing Date</label><input type="date" wire:model="closingDate" class="{{ $inputClass }}"></div>
                        <div class="sm:col-span-2"><label class="{{ $labelClass }}">Delivery Address</label><input wire:model="deliveryAddress" placeholder="Full delivery address" class="{{ $inputClass }}"></div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-400">By submitting, you agree that your requirements will be shared with approved Cadical suppliers.</p>
                    <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto bg-cadical-500 hover:bg-cadical-700 disabled:opacity-70 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-colors">
                        <span wire:loading.remove>Submit RFQ</span>
                        <span wire:loading>Submitting…</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
