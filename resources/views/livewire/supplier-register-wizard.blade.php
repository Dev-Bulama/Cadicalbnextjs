@php
    $steps = ['Company Info' => 'building-2', 'KYC Documents' => 'file-text', 'Review & Submit' => 'shield'];
    $inputClass = 'w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
@endphp
<div class="min-h-screen bg-slate-50">
    @if ($submitted)
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="max-w-md w-full bg-white rounded-2xl border border-slate-100 text-center p-10">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Application Submitted</h2>
                <p class="text-slate-500 text-sm mb-4">
                    Thank you for applying to become a Cadical supplier. Our team will review your application
                    and contact you within 3–5 business days.
                </p>
                <div class="p-3 bg-slate-50 rounded-lg mb-6 text-sm text-left">
                    <p class="text-slate-400 text-xs mb-1">What happens next?</p>
                    <ul class="space-y-1 text-xs text-slate-600">
                        <li>✓ Document verification by our compliance team</li>
                        <li>✓ Background and license check</li>
                        <li>✓ Approval notification via email</li>
                        <li>✓ Portal access granted upon approval</li>
                    </ul>
                </div>
                <a href="{{ url('/') }}" class="inline-block px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Return to Home</a>
            </div>
        </div>
    @else
        <div class="border-b border-slate-200 bg-white px-8 py-5">
            <div class="max-w-3xl mx-auto flex items-center gap-3">
                <div class="w-9 h-9 bg-cadical-500 rounded-lg flex items-center justify-center">
                    <i data-lucide="truck" class="w-[18px] h-[18px] text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-slate-900">Become a Cadical Supplier</h1>
                    <p class="text-xs text-slate-400">Join Nigeria's leading medical equipment marketplace</p>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-8 py-10 space-y-6">
            <div class="flex items-center gap-0">
                @foreach ($steps as $label => $icon)
                    @php $i = $loop->index; @endphp
                    <div class="flex items-center flex-1">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ $i === $step ? 'bg-cadical-500 text-white' : ($i < $step ? 'text-emerald-600' : 'text-slate-400') }}">
                            <i data-lucide="{{ $i < $step ? 'check-circle' : $icon }}" class="w-4 h-4"></i>
                            <span class="hidden sm:block">{{ $label }}</span>
                        </div>
                        @if (! $loop->last)
                            <div class="flex-1 h-px mx-2 {{ $i < $step ? 'bg-emerald-300' : 'bg-slate-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if ($step === 0)
                <div class="bg-white rounded-2xl border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="text-xs font-medium text-slate-500">Company Name *</label><input wire:model="companyName" placeholder="MedEquip Solutions Ltd." class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Contact Person *</label><input wire:model="contactName" placeholder="John Adeyemi" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Business Email *</label><input type="email" wire:model="email" placeholder="info@company.ng" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Phone Number *</label><input wire:model="phone" placeholder="+234 801 234 5678" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Alt. Phone</label><input wire:model="altPhone" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Website</label><input type="url" wire:model="website" placeholder="https://company.ng" class="{{ $inputClass }} mt-1"></div>
                        <div>
                            <label class="text-xs font-medium text-slate-500">State *</label>
                            <select wire:model="state" class="{{ $inputClass }} mt-1">
                                <option value="">Select state…</option>
                                @foreach (self::STATES as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="text-xs font-medium text-slate-500">City *</label><input wire:model="city" class="{{ $inputClass }} mt-1"></div>
                    </div>
                    <div class="mt-4"><label class="text-xs font-medium text-slate-500">Address *</label><input wire:model="address" placeholder="25 Medical Drive, Ikeja" class="{{ $inputClass }} mt-1"></div>
                    <div class="mt-4"><label class="text-xs font-medium text-slate-500">Company Description</label><textarea wire:model="description" rows="3" placeholder="Brief description of your company and products…" class="{{ $inputClass }} mt-1"></textarea></div>
                    <div class="mt-4">
                        <label class="text-xs font-medium text-slate-500">Supply Categories *</label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach (self::CATEGORIES as $cat)
                                <button type="button" wire:click="toggleCategory('{{ $cat }}')" class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ in_array($cat, $category, true) ? 'bg-cadical-500 text-white border-cadical-500' : 'border-slate-200 text-slate-500 hover:border-cadical-500/50' }}">
                                    {{ $cat }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button wire:click="next" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Next: KYC Documents</button>
                    </div>
                </div>
            @elseif ($step === 1)
                <div class="bg-white rounded-2xl border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">KYC & Business Documents</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="text-xs font-medium text-slate-500">CAC Registration Number</label><input wire:model="cacNumber" placeholder="RC1234567" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Tax Identification Number (TIN)</label><input wire:model="taxId" placeholder="12345678-0001" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">NAFDAC Number (if applicable)</label><input wire:model="nafdacNumber" placeholder="A7-1234" class="{{ $inputClass }} mt-1"></div>
                        <div><label class="text-xs font-medium text-slate-500">Year Established</label><input type="number" wire:model="yearEstablished" placeholder="2010" class="{{ $inputClass }} mt-1"></div>
                    </div>
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                        <p class="font-semibold mb-1">Document Upload</p>
                        <p class="text-xs">After your application is approved, our team will contact you to collect certified copies of:</p>
                        <ul class="text-xs mt-1 list-disc list-inside space-y-0.5">
                            <li>CAC Certificate of Incorporation</li>
                            <li>NAFDAC Registration (for pharma/devices)</li>
                            <li>PCN License (for pharmaceuticals)</li>
                            <li>Tax Clearance Certificate</li>
                            <li>Proof of Business Address</li>
                        </ul>
                    </div>
                    <div class="flex justify-between pt-4">
                        <button wire:click="back" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Back</button>
                        <button wire:click="next" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Next: Review</button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-900 mb-4">Review Your Application</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
                        @foreach ([['Company', $companyName], ['Contact', $contactName], ['Email', $email], ['Phone', $phone], ['Location', "$city, $state"], ['CAC', $cacNumber ?: 'Not provided'], ['NAFDAC', $nafdacNumber ?: 'Not provided'], ['Tax ID', $taxId ?: 'Not provided']] as [$label, $value])
                            <div class="flex justify-between py-1.5 border-b border-slate-100">
                                <span class="text-slate-400">{{ $label }}</span>
                                <span class="font-medium text-slate-900 text-right">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-slate-900 mb-2">Supply Categories</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($category as $c)
                                <span class="px-2 py-0.5 bg-cadical-500/10 text-cadical-500 text-xs rounded-full font-medium">{{ $c }}</span>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-4">
                        By submitting, you confirm that all provided information is accurate and you agree to
                        Cadical's Supplier Terms of Service.
                    </p>
                    <div class="flex justify-between pt-4">
                        <button wire:click="back" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Back</button>
                        <button wire:click="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold disabled:opacity-60">
                            <span wire:loading.remove>Submit Application</span>
                            <span wire:loading>Submitting…</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
