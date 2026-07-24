@php
    $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
    $labelClass = 'block text-sm font-medium text-slate-700 mb-1.5';
@endphp
<div>
@if ($screen === 'welcome')
    <div class="min-h-screen bg-gradient-to-br from-cadical-700 via-cadical-500 to-emerald-700 text-white">
        <div class="max-w-3xl mx-auto px-4 pt-28 pb-16 text-center">
            <span class="inline-block text-white/80 text-xs font-semibold tracking-widest uppercase border border-white/20 bg-white/10 px-4 py-1.5 rounded-full mb-6">Institutional Portal</span>
            <h1 class="text-3xl md:text-5xl font-bold mb-5 leading-tight">Healthcare Supply, <br><span class="text-emerald-300">Simplified for Institutions.</span></h1>
            <p class="text-white/75 max-w-xl mx-auto mb-8">Join hundreds of Nigerian hospitals, clinics, and laboratories sourcing medical supplies, scheduling maintenance, and managing procurement through one verified portal.</p>
            <button wire:click="start" class="bg-accent hover:bg-accent-600 text-white px-7 py-3.5 rounded-xl font-semibold transition-all hover:scale-105 shadow-lg">Get Started, It's Free &rarr;</button>
            <p class="text-white/50 text-xs mt-4">2-3 day approval &middot; No setup fees &middot; Dedicated account manager</p>
        </div>

        <div class="max-w-5xl mx-auto px-4 pb-16 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([
                ['🩺', 'Medical Supply', 'Equipment, consumables & pharmaceuticals at institutional rates'],
                ['🔧', 'Repair & Maintenance', 'Scheduled servicing, calibration & on-site technical support'],
                ['💬', 'Supply Consultations', 'Expert advisory on procurement, formulary & supply chain'],
                ['🧪', 'Reagents & Lab', 'Full range of reagents, assay kits & lab chemicals'],
            ] as [$icon, $title, $desc])
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                    <span class="text-2xl block mb-2">{{ $icon }}</span>
                    <div class="font-semibold text-sm mb-1">{{ $title }}</div>
                    <div class="text-white/60 text-xs leading-relaxed">{{ $desc }}</div>
                </div>
            @endforeach
        </div>

        <div class="max-w-3xl mx-auto px-4 pb-20">
            <p class="text-center text-white/50 text-xs uppercase tracking-widest mb-6">How it works</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach (['Fill the 5-step form', 'Submit documents', 'Get approved in 2–3 days', 'Access your portal'] as $i => $s)
                    <div class="text-center">
                        <div class="w-9 h-9 mx-auto rounded-full bg-white/15 flex items-center justify-center font-bold text-sm mb-2">{{ $i + 1 }}</div>
                        <div class="text-xs text-white/70">{{ $s }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@elseif ($screen === 'form')
    <div class="min-h-screen bg-slate-50 pt-24 pb-16 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 sm:px-8 py-5">
                    <h2 class="text-xl font-bold text-slate-900">{{ ['Institution Profile', 'Location & Contact', 'Service Needs', 'Compliance', 'Account Setup'][$step] }}</h2>
                    <p class="text-sm text-slate-400">Step {{ $step + 1 }} of 5</p>
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Step 0 --}}
                    @if ($step === 0)
                        <div class="space-y-4">
                            <div><label class="{{ $labelClass }}">Institution Name *</label><input wire:model="instName" placeholder="e.g. Lagos University Teaching Hospital" class="{{ $inputClass }}"></div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">Institution Type *</label>
                                    <select wire:model="instType" class="{{ $inputClass }}"><option value="">Select type…</option>@foreach (\App\Livewire\InstitutionalPortal::INST_TYPES as $t)<option>{{ $t }}</option>@endforeach</select>
                                </div>
                                <div><label class="{{ $labelClass }}">CAC Registration No. *</label><input wire:model="cac" placeholder="RC-000000" class="{{ $inputClass }}"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div><label class="{{ $labelClass }}">Year Established</label><input type="number" wire:model="year" placeholder="e.g. 2005" class="{{ $inputClass }}"></div>
                                <div><label class="{{ $labelClass }}">Total Staff Count</label><input type="number" wire:model="staff" placeholder="e.g. 120" class="{{ $inputClass }}"></div>
                                <div><label class="{{ $labelClass }}">Bed Capacity</label><input type="number" wire:model="beds" placeholder="e.g. 50" class="{{ $inputClass }}"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Step 1 --}}
                    @if ($step === 1)
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">State *</label>
                                    <select wire:model="state" class="{{ $inputClass }}"><option value="">Select state…</option>@foreach (\App\Livewire\InstitutionalPortal::STATES as $s)<option>{{ $s }}</option>@endforeach</select>
                                </div>
                                <div><label class="{{ $labelClass }}">LGA *</label><input wire:model="lga" placeholder="Local Government Area" class="{{ $inputClass }}"></div>
                            </div>
                            <div><label class="{{ $labelClass }}">Full Address *</label><textarea wire:model="address" placeholder="Street, area, city" rows="2" class="{{ $inputClass }}"></textarea></div>
                            <div class="border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Primary Contact Person</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div><label class="{{ $labelClass }}">Full Name *</label><input wire:model="contactName" placeholder="e.g. Dr. Chioma Eze" class="{{ $inputClass }}"></div>
                                    <div><label class="{{ $labelClass }}">Designation *</label><input wire:model="designation" placeholder="e.g. Medical Director" class="{{ $inputClass }}"></div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div><label class="{{ $labelClass }}">Phone Number *</label><input wire:model="phone" placeholder="+234 800 000 0000" class="{{ $inputClass }}"></div>
                                    <div><label class="{{ $labelClass }}">Alternate Phone</label><input wire:model="altPhone" placeholder="+234 800 000 0000" class="{{ $inputClass }}"></div>
                                    <div><label class="{{ $labelClass }}">Institutional Email *</label><input type="email" wire:model="email" placeholder="admin@facility.ng" class="{{ $inputClass }}"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Step 2 --}}
                    @if ($step === 2)
                        <div class="space-y-5">
                            <p class="text-sm text-slate-500">Select all services your institution requires. Sub-fields expand per selection.</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach (\App\Livewire\InstitutionalPortal::SERVICES as $sv)
                                    <button type="button" wire:click="toggle('services', '{{ $sv['id'] }}')" class="relative p-3 rounded-xl border text-center transition-colors {{ $this->hasService($sv['id']) ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                        <span class="text-xl block mb-1">{{ $sv['icon'] }}</span>
                                        <span class="text-xs font-semibold text-slate-900 block">{{ $sv['name'] }}</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">{{ $sv['desc'] }}</span>
                                        @if ($this->hasService($sv['id']))<span class="absolute top-1 right-1 w-4 h-4 bg-cadical-500 text-white rounded-full text-[10px] flex items-center justify-center">✓</span>@endif
                                    </button>
                                @endforeach
                            </div>

                            @foreach ([
                                ['specialist', 'specialistOpts', 'Repair & Maintenance Areas', \App\Livewire\InstitutionalPortal::SPECIALIST_OPTS],
                                ['consultations', 'consultOpts', 'Supply Consultation Focus', \App\Livewire\InstitutionalPortal::CONSULT_OPTS],
                                ['reagents', 'reagentOpts', 'Reagent Categories', \App\Livewire\InstitutionalPortal::REAGENT_OPTS],
                                ['educational', 'eduOpts', 'Educational Material Formats', \App\Livewire\InstitutionalPortal::EDU_OPTS],
                            ] as [$svcId, $field, $label, $opts])
                                @if ($this->hasService($svcId))
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700 mb-2">{{ $label }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($opts as $opt)
                                                <button type="button" wire:click="toggle('{{ $field }}', '{{ $opt }}')" class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ in_array($opt, $this->$field) ? 'bg-cadical-500 text-white border-cadical-500' : 'border-slate-200 text-slate-500' }}">{{ $opt }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">Monthly Procurement Volume</label>
                                    <select wire:model="volume" class="{{ $inputClass }}"><option value="">Select range…</option>@foreach (\App\Livewire\InstitutionalPortal::VOLUMES as $v)<option>{{ $v }}</option>@endforeach</select>
                                </div>
                                <div><label class="{{ $labelClass }}">Additional Requirements</label><input wire:model="notes" placeholder="Specific brands, supply frequency…" class="{{ $inputClass }}"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Step 3 --}}
                    @if ($step === 3)
                        <div class="space-y-5">
                            <p class="text-sm text-slate-500">Cadical verifies all institutions before granting portal access. All documents are stored securely.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">NAFDAC Compliance Status *</label>
                                    <select wire:model="nafdac" class="{{ $inputClass }}">
                                        <option value="">Select…</option>
                                        @foreach (['Fully licensed', 'Compliant – renewal in progress', 'Not applicable to institution type', 'Licence application in progress'] as $opt)<option>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div><label class="{{ $labelClass }}">PCN / Other Licence No.</label><input wire:model="pcn" placeholder="e.g. PCN/LIC/2024/XXXXX" class="{{ $inputClass }}"></div>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-700 mb-3">Document Uploads</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div><label class="{{ $labelClass }}">CAC Certificate *</label><input type="file" wire:model="cacDoc" class="text-xs w-full"><p class="text-[10px] text-slate-400 mt-1">PDF or image &middot; max 5 MB</p></div>
                                    <div><label class="{{ $labelClass }}">NAFDAC / Pharmacy Licence</label><input type="file" wire:model="nafdacDoc" class="text-xs w-full"><p class="text-[10px] text-slate-400 mt-1">PDF or image &middot; max 5 MB</p></div>
                                    <div><label class="{{ $labelClass }}">Other Regulatory Document</label><input type="file" wire:model="otherDoc" class="text-xs w-full"><p class="text-[10px] text-slate-400 mt-1">PDF or image &middot; max 5 MB</p></div>
                                </div>
                            </div>
                            <label class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4 cursor-pointer">
                                <input type="checkbox" wire:model="confirmDocs" class="w-4 h-4 mt-0.5 accent-cadical-500">
                                <span class="text-xs text-amber-800 leading-relaxed">I confirm all submitted documents and information are accurate. I understand that false submissions will result in rejection from Cadical's institutional programme. *</span>
                            </label>
                        </div>
                    @endif

                    {{-- Step 4 --}}
                    @if ($step === 4)
                        <div class="space-y-5">
                            <div><label class="{{ $labelClass }}">Account Email Address *</label><input type="email" wire:model="accountEmail" placeholder="This will be your portal login email" class="{{ $inputClass }}"></div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="{{ $labelClass }}">Password *</label><input type="password" wire:model="password" placeholder="Minimum 8 characters" class="{{ $inputClass }}"></div>
                                <div><label class="{{ $labelClass }}">Confirm Password *</label><input type="password" wire:model="confirmPw" placeholder="Repeat password" class="{{ $inputClass }}"></div>
                            </div>

                            <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                                <p class="text-xs font-semibold text-slate-700 mb-3">📋 Application Summary</p>
                                <div class="grid grid-cols-2 gap-y-2 text-xs">
                                    @foreach ([
                                        ['Institution', $instName ?: '—'], ['Type', $instType ?: '—'], ['State', $state ?: '—'],
                                        ['Contact', $contactName ?: '—'], ['Services', count($services) ? count($services).' selected' : 'None'],
                                        ['Volume', $volume ?: 'Not specified'],
                                    ] as [$k, $v])
                                        <span class="text-slate-400">{{ $k }}</span><span class="text-slate-900 font-medium text-right">{{ $v }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-start gap-3 cursor-pointer"><input type="checkbox" wire:model="agreeTerms" class="w-4 h-4 mt-0.5 accent-cadical-500"><span class="text-xs text-slate-600">I agree to Cadical's <a href="{{ url('/terms') }}" target="_blank" class="text-cadical-500 underline">Terms of Service</a> and institutional partnership agreement. *</span></label>
                                <label class="flex items-start gap-3 cursor-pointer"><input type="checkbox" wire:model="agreePrivacy" class="w-4 h-4 mt-0.5 accent-cadical-500"><span class="text-xs text-slate-600">I accept the <a href="{{ url('/privacy-policy') }}" target="_blank" class="text-cadical-500 underline">Privacy Policy</a>. *</span></label>
                                <label class="flex items-start gap-3 cursor-pointer"><input type="checkbox" wire:model="newsletter" class="w-4 h-4 mt-0.5 accent-cadical-500"><span class="text-xs text-slate-600">Receive product updates, regulatory news, and exclusive offers from Cadical.</span></label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center px-6 sm:px-8 py-5 border-t border-slate-100">
                    @if ($step > 0)
                        <button wire:click="back" class="px-5 py-2.5 rounded-lg text-sm font-semibold border border-slate-200 text-slate-500 hover:border-cadical-500 hover:text-cadical-500 transition-colors">← Back</button>
                    @else
                        <span></span>
                    @endif

                    @if ($step < 4)
                        <button wire:click="next" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-cadical-500 text-white hover:bg-cadical-700 transition-colors">Continue →</button>
                    @else
                        <button wire:click="submit" wire:loading.attr="disabled" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-cadical-500 text-white hover:bg-cadical-700 disabled:opacity-70 transition-colors">
                            <span wire:loading.remove>Submit Application</span>
                            <span wire:loading>Submitting…</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@elseif ($screen === 'confirm')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-16">
        <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 p-8 text-center">
            <div class="text-5xl mb-3">🎉</div>
            <h2 class="text-2xl font-bold text-slate-900 mb-2">You're in the queue!</h2>
            <p class="text-slate-500 text-sm mb-6">{{ $instName ?: 'Your institution' }}'s application has been received. We'll contact <strong>{{ $accountEmail }}</strong> once verified.</p>
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6">
                <p class="text-xs text-slate-400">Your Reference Number</p>
                <p class="font-mono font-bold text-lg text-slate-900">{{ $refNo }}</p>
            </div>
            <div class="text-left space-y-3 mb-2">
                @foreach ([['Application submitted', true], ['Document review (1-2 days)', false], ['Verification call', false], ['Portal access granted', false]] as [$label, $done])
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 {{ $done ? 'bg-cadical-500' : 'bg-slate-100 border border-slate-200' }}">
                            @if ($done)<span class="text-white text-[10px]">✓</span>@endif
                        </div>
                        <span class="text-sm {{ $done ? 'font-semibold text-slate-900' : 'text-slate-400' }}">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
</div>
