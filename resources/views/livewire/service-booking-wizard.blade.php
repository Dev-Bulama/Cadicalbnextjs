@php
    $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
    $labelClass = 'block text-sm font-medium text-slate-700 mb-1.5';
    $sw = \App\Models\HomeSection::content('service_booking_wizard', ['meta' => []])['meta'];
@endphp
<div class="min-h-screen bg-slate-50">
    @if ($submitted)
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="max-w-md w-full text-center bg-white rounded-2xl border border-slate-200 pt-10 pb-8 px-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-600"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">{{ $sw['confirmation_heading'] ?? 'Booking Confirmed' }}</h2>
                <p class="text-slate-500 text-sm mb-4">{{ $sw['confirmation_message'] ?? '' }}</p>
                <div class="p-3 bg-slate-50 rounded-lg mb-4">
                    <p class="text-xs text-slate-400">Booking Reference</p>
                    <p class="font-mono font-bold text-lg text-slate-900">{{ $bookingCode }}</p>
                </div>
                <div class="text-xs text-slate-500 text-left space-y-1 mb-6 p-3 bg-blue-50 rounded-lg">
                    <p class="font-medium text-cadical-700 mb-1">What happens next?</p>
                    @foreach ($sw['next_steps'] ?? [] as $step)
                        <p>✓ {{ $step }}</p>
                    @endforeach
                </div>
                <a href="{{ url('/') }}" class="inline-block bg-cadical-500 hover:bg-cadical-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors">Return to Home</a>
            </div>
        </div>
    @else
        <div class="border-b border-slate-200 bg-white px-4 md:px-6 py-4">
            <div class="max-w-3xl mx-auto flex items-center gap-3">
                <div class="w-8 h-8 bg-cadical-500 rounded-lg flex items-center justify-center"><i data-lucide="wrench" class="w-4 h-4 text-white"></i></div>
                <div>
                    <h1 class="font-bold text-slate-900">{{ $sw['banner_title'] ?? 'Book a Service' }}</h1>
                    <p class="text-xs text-slate-400">{{ $sw['banner_sub'] ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 md:px-6 py-8 space-y-6">
            {{-- Step progress --}}
            <div class="flex items-center gap-1 overflow-x-auto pb-2">
                @foreach (\App\Livewire\ServiceBookingWizard::STEPS as $i => $s)
                    <div class="flex items-center shrink-0">
                        <div class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $i === $step ? 'bg-cadical-500 text-white' : ($i < $step ? 'text-emerald-600 bg-emerald-50' : 'text-slate-400') }}">
                            <i data-lucide="{{ $i < $step ? 'check-circle' : $s['icon'] }}" class="w-[13px] h-[13px]"></i>
                            <span class="hidden sm:block">{{ $s['label'] }}</span>
                            <span class="sm:hidden">{{ $i + 1 }}</span>
                        </div>
                        @if ($i < count(\App\Livewire\ServiceBookingWizard::STEPS) - 1)
                            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300 mx-0.5"></i>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                {{-- Step 0: Equipment --}}
                @if ($step === 0)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="package" class="w-4 h-4 text-cadical-500"></i> Equipment Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2"><label class="{{ $labelClass }}">Equipment Name *</label><input wire:model="equipmentName" placeholder="e.g. Philips Ventilator" class="{{ $inputClass }}"></div>
                        <div><label class="{{ $labelClass }}">Brand / Manufacturer</label><input wire:model="equipmentBrand" placeholder="Philips, GE, Siemens…" class="{{ $inputClass }}"></div>
                        <div><label class="{{ $labelClass }}">Model Number</label><input wire:model="equipmentModel" placeholder="V60 Plus" class="{{ $inputClass }}"></div>
                        <div class="sm:col-span-2"><label class="{{ $labelClass }}">Serial Number</label><input wire:model="equipmentSerial" placeholder="SN12345678" class="{{ $inputClass }}"></div>
                    </div>
                @endif

                {{-- Step 1: Service Type --}}
                @if ($step === 1)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="wrench" class="w-4 h-4 text-cadical-500"></i> Select Service Type</h3>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        @foreach (\App\Livewire\ServiceBookingWizard::SERVICE_TYPES as $s)
                            <button type="button" wire:click="$set('serviceType', '{{ $s['value'] }}')" class="p-3 rounded-lg border-2 text-left transition-all {{ $serviceType === $s['value'] ? 'border-cadical-500 bg-cadical-500/5' : 'border-slate-200 hover:border-cadical-500/30' }}">
                                <div class="text-lg mb-1">{{ $s['icon'] }}</div>
                                <p class="text-sm font-semibold text-slate-900">{{ $s['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $s['desc'] }}</p>
                            </button>
                        @endforeach
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Urgency Level</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach (\App\Livewire\ServiceBookingWizard::URGENCY_OPTS as $u)
                                <button type="button" wire:click="$set('urgency', '{{ $u['value'] }}')" class="p-3 rounded-lg border-2 text-left transition-all {{ $urgency === $u['value'] ? 'border-cadical-500 bg-cadical-500/5' : 'border-slate-200 hover:border-cadical-500/30' }}">
                                    <p class="text-sm font-semibold {{ $u['color'] }}">{{ $u['label'] }}</p>
                                    <p class="text-xs text-slate-400">{{ $u['desc'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Step 2: Describe Issue --}}
                @if ($step === 2)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="clipboard-list" class="w-4 h-4 text-cadical-500"></i> Describe the Issue</h3>
                    <div class="space-y-4">
                        <div><label class="{{ $labelClass }}">Issue Description *</label><textarea wire:model="issueDescription" rows="4" placeholder="Describe what's happening with the equipment…" class="{{ $inputClass }}"></textarea></div>

                        @if ($this->isRepair)
                            <div><label class="{{ $labelClass }}">Fault Description</label><textarea wire:model="notes" rows="3" placeholder="Specific fault codes, error messages, symptoms…" class="{{ $inputClass }}"></textarea></div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="{{ $labelClass }}">Issue Severity</label>
                                    <select wire:model="severity" class="{{ $inputClass }}">
                                        <option value="">Select severity…</option>
                                        <option value="low">Low: Equipment works partially</option>
                                        <option value="medium">Medium: Reduced performance</option>
                                        <option value="high">High: Equipment not functional</option>
                                        <option value="critical">Critical: Patient safety risk</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Equipment Condition</label>
                                    <select wire:model="equipmentCondition" class="{{ $inputClass }}">
                                        <option value="">Select condition…</option>
                                        <option value="good">Good (minor issue)</option>
                                        <option value="fair">Fair (moderate damage)</option>
                                        <option value="poor">Poor (extensive damage)</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($this->isInstallation)
                            <div>
                                <label class="{{ $labelClass }}">Site Readiness</label>
                                <select wire:model="siteReadiness" class="{{ $inputClass }}">
                                    <option value="">Is site ready for installation?</option>
                                    <option value="ready">Site is ready</option>
                                    <option value="needs_prep">Site needs preparation</option>
                                    <option value="not_sure">Not sure</option>
                                </select>
                            </div>
                            <div><label class="{{ $labelClass }}">Electrical / Infrastructure Requirements</label><textarea wire:model="electricalReq" rows="2" placeholder="Power supply specs, voltage, special outlets required…" class="{{ $inputClass }}"></textarea></div>
                        @endif

                        @if (! $this->isRepair)
                            <div><label class="{{ $labelClass }}">Additional Notes</label><textarea wire:model="notes" rows="2" placeholder="Any other information you want us to know…" class="{{ $inputClass }}"></textarea></div>
                        @endif
                    </div>
                @endif

                {{-- Step 3: Media --}}
                @if ($step === 3)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="camera" class="w-4 h-4 text-cadical-500"></i> Upload Equipment Images</h3>
                    <div class="space-y-4">
                        <label class="block border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-cadical-500 transition-colors">
                            <i data-lucide="camera" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                            <p class="font-medium text-sm text-slate-700">Upload Equipment Photos</p>
                            <p class="text-xs text-slate-400 mt-1">Photos help our technicians prepare and bring the right tools</p>
                            <span class="inline-block mt-3 border border-slate-200 rounded-lg px-4 py-1.5 text-sm font-medium text-slate-600">Select Images</span>
                            <input type="file" wire:model="photos" multiple accept="image/*" class="hidden">
                        </label>

                        <div wire:loading wire:target="photos" class="text-xs text-slate-400">Uploading…</div>

                        @if (count($photos) > 0)
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                                @foreach ($photos as $i => $photo)
                                    <div class="relative">
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full aspect-square object-cover rounded-lg border border-slate-200">
                                        <button type="button" wire:click="removePhoto({{ $i }})" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">✕</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <p class="text-xs text-slate-400 text-center">Supported: JPG, PNG · Max 10 files · 10MB each</p>
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800">
                            <strong>Tip:</strong> Take photos showing the issue area, model/serial number label, and surrounding environment.
                        </div>
                    </div>
                @endif

                {{-- Step 4: Schedule --}}
                @if ($step === 4)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="calendar" class="w-4 h-4 text-cadical-500"></i> Preferred Schedule</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="{{ $labelClass }}">Preferred Date *</label><input type="date" wire:model="preferredDate" min="{{ now()->toDateString() }}" class="{{ $inputClass }}"></div>
                        <div>
                            <label class="{{ $labelClass }}">Preferred Time Slot</label>
                            <select wire:model="preferredTimeSlot" class="{{ $inputClass }}">
                                <option value="">Select time…</option>
                                <option value="8am-10am">8:00 AM – 10:00 AM</option>
                                <option value="10am-12pm">10:00 AM – 12:00 PM</option>
                                <option value="12pm-2pm">12:00 PM – 2:00 PM</option>
                                <option value="2pm-4pm">2:00 PM – 4:00 PM</option>
                                <option value="4pm-6pm">4:00 PM – 6:00 PM</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2"><label class="{{ $labelClass }}">Alternate Date <span class="text-slate-400">(optional)</span></label><input type="date" wire:model="alternateDate" min="{{ now()->toDateString() }}" class="{{ $inputClass }}"></div>
                    </div>
                @endif

                {{-- Step 5: Location --}}
                @if ($step === 5)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="map-pin" class="w-4 h-4 text-cadical-500"></i> Service Location</h3>
                    <div class="space-y-4">
                        <div><label class="{{ $labelClass }}">Site Address *</label><input wire:model="siteAddress" placeholder="25 Hospital Road, Surulere" class="{{ $inputClass }}"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="{{ $labelClass }}">City *</label><input wire:model="siteCity" placeholder="Lagos" class="{{ $inputClass }}"></div>
                            <div><label class="{{ $labelClass }}">State *</label><input wire:model="siteState" placeholder="Lagos" class="{{ $inputClass }}"></div>
                            <div><label class="{{ $labelClass }}">On-site Contact Name</label><input wire:model="siteContact" placeholder="Dr. Emeka Obi" class="{{ $inputClass }}"></div>
                            <div><label class="{{ $labelClass }}">On-site Contact Phone</label><input wire:model="sitePhone" placeholder="+234 801 234 5678" class="{{ $inputClass }}"></div>
                        </div>
                    </div>
                @endif

                {{-- Step 6: Review --}}
                @if ($step === 6)
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2 mb-5"><i data-lucide="check-circle" class="w-4 h-4 text-cadical-500"></i> Review Your Request</h3>
                    <div class="space-y-2 mb-4">
                        @foreach ([
                            ['Equipment', $equipmentName.($equipmentBrand ? " ({$equipmentBrand})" : '')],
                            ['Service', $this->serviceTypeLabel],
                            ['Urgency', $this->urgencyLabel],
                            ['Location', "{$siteAddress}, {$siteCity}, {$siteState}"],
                            ['Preferred Date', $preferredDate ?: 'Not specified'],
                            ['Time Slot', $preferredTimeSlot ?: 'Flexible'],
                        ] as [$label, $value])
                            <div class="flex justify-between py-2 border-b border-slate-100 text-sm">
                                <span class="text-slate-400">{{ $label }}</span>
                                <span class="font-medium text-slate-900 text-right max-w-[60%]">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg mb-4">
                        <p class="text-xs font-medium text-slate-700 mb-1">Issue Description</p>
                        <p class="text-xs text-slate-500">{{ $issueDescription }}</p>
                    </div>
                    <p class="text-xs text-slate-400">By submitting, you agree to Cadical's Service Terms. A technician will be assigned and you'll receive SMS/email updates.</p>
                @endif
            </div>

            {{-- Nav --}}
            <div class="flex justify-between">
                <button wire:click="back" @disabled($step === 0) class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold border border-slate-200 text-slate-500 disabled:opacity-40 hover:border-cadical-500 hover:text-cadical-500 transition-colors">
                    <i data-lucide="chevron-left" class="w-[15px] h-[15px]"></i> Back
                </button>
                @if ($step < count(\App\Livewire\ServiceBookingWizard::STEPS) - 1)
                    <button wire:click="next" class="flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold bg-cadical-500 text-white hover:bg-cadical-700 transition-colors">
                        Next <i data-lucide="chevron-right" class="w-[15px] h-[15px]"></i>
                    </button>
                @else
                    <button wire:click="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-cadical-500 text-white hover:bg-cadical-700 disabled:opacity-70 transition-colors">
                        <span wire:loading.remove>Confirm Booking</span>
                        <span wire:loading>Submitting…</span>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
