@php
    $inputClass = 'w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-900 bg-white transition-all focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500';
@endphp
<section class="max-w-[1100px] mx-auto px-4 sm:px-6 md:px-12 py-8 md:py-10 pb-14">
    <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-7 items-start">

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            @if ($bookingRef)
                {{-- Confirmation --}}
                <div class="text-center px-8 py-12">
                    <div class="w-[72px] h-[72px] bg-emerald-50 rounded-full flex items-center justify-center text-3xl mx-auto mb-5">✅</div>
                    <h2 class="font-bold text-2xl text-slate-900 mb-2.5">Booking Received.</h2>
                    <p class="text-sm text-slate-500 leading-relaxed max-w-[380px] mx-auto mb-6">
                        Thank you. Your booking request has been submitted. The Cadical team will confirm your appointment within 24 hours via phone or WhatsApp.
                    </p>
                    <div class="inline-block bg-blue-50 border border-slate-200 rounded-lg px-5 py-2.5 text-sm text-cadical-500 font-semibold mb-6">Ref: {{ $bookingRef }}</div>
                    <div class="flex gap-3 justify-center flex-wrap">
                        <a href="https://wa.me/2347076175550" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-cadical-500 text-white rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors">Message Us on WhatsApp</a>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-slate-200 text-slate-500 rounded-lg text-sm font-semibold hover:border-cadical-500 hover:text-cadical-500 transition-colors">Back to Home</a>
                    </div>
                </div>
            @else
                {{-- Steps bar --}}
                <div class="border-b border-slate-200 px-4 sm:px-8 flex items-center">
                    @foreach ([1 => 'Service', 2 => 'Details', 3 => 'Date', 4 => 'Confirm'] as $id => $label)
                        <div class="flex items-center gap-2 py-4 flex-1 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 {{ $id < $step ? 'bg-emerald-600 text-white' : ($id === $step ? 'bg-cadical-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                {{ $id < $step ? '✓' : $id }}
                            </div>
                            <span class="text-xs font-medium truncate {{ $id < $step ? 'text-emerald-600' : ($id === $step ? 'text-cadical-500 font-semibold' : 'text-slate-400') }}">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Step 1: Service --}}
                    @if ($step === 1)
                        <p class="font-bold text-xl text-slate-900 mb-1.5">What do you need?</p>
                        <p class="text-sm text-slate-500 mb-7">Select the service that fits your situation.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <div wire:click="selectService('maintenance')" class="relative border rounded-lg p-4 cursor-pointer transition-colors {{ $service === 'maintenance' ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                @if ($service === 'maintenance')<span class="absolute top-2.5 right-3 w-5 h-5 bg-cadical-500 text-white rounded-full text-[11px] font-bold flex items-center justify-center">✓</span>@endif
                                <span class="text-2xl mb-2 block">🔧</span>
                                <h4 class="text-sm font-semibold text-slate-900 mb-1">Equipment Maintenance & Repair</h4>
                                <p class="text-xs text-slate-500">Servicing, calibration or repair of medical equipment at your facility</p>
                            </div>
                            <div wire:click="selectService('consultation')" class="relative border rounded-lg p-4 cursor-pointer transition-colors {{ $service === 'consultation' ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                @if ($service === 'consultation')<span class="absolute top-2.5 right-3 w-5 h-5 bg-cadical-500 text-white rounded-full text-[11px] font-bold flex items-center justify-center">✓</span>@endif
                                <span class="text-2xl mb-2 block">💬</span>
                                <h4 class="text-sm font-semibold text-slate-900 mb-1">Supply Consultation</h4>
                                <p class="text-xs text-slate-500">Expert advice on healthcare procurement, equipment selection or supply chain</p>
                            </div>
                        </div>

                        @if ($service === 'maintenance')
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Equipment Type *</label>
                                <select wire:model="equipmentType" class="{{ $inputClass }}">
                                    <option value="">Select equipment type</option>
                                    @foreach (['Dialysis Machine', 'Blood Pressure Monitor', 'ECG / EKG Machine', 'Ultrasound / Imaging Equipment', 'Infusion Pump', 'Ventilator', 'Laboratory Equipment', 'Other Medical Equipment'] as $opt)
                                        <option>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nature of Issue *</label>
                                <select wire:model="issueType" class="{{ $inputClass }}">
                                    <option value="">What needs to be done?</option>
                                    @foreach (['Routine Servicing / Preventive Maintenance', 'Equipment Not Functioning', 'Calibration Required', 'Parts Replacement', 'Post-Repair Inspection', 'Unsure — needs assessment'] as $opt)
                                        <option>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Urgency</label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    @foreach ([['routine', '📅', 'Routine', 'Within 1 week'], ['soon', '⚡', 'Soon', 'Within 48hrs'], ['urgent', '🚨', 'Urgent', 'Same day']] as [$id, $icon, $label, $sub])
                                        <div wire:click="$set('urgency', '{{ $id }}')" class="relative border rounded-lg px-3 py-2.5 cursor-pointer text-center transition-colors {{ $urgency === $id ? ($id === 'urgent' ? 'border-red-400 bg-red-50' : 'border-cadical-500 bg-blue-50') : 'border-slate-200 hover:border-cadical-500' }}">
                                            <span class="text-lg mb-1 block">{{ $icon }}</span>
                                            <h4 class="text-xs font-semibold text-slate-900 mb-0.5">{{ $label }}</h4>
                                            <p class="text-[11px] text-slate-500">{{ $sub }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($service === 'consultation')
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Consultation Type *</label>
                                <select wire:model="consultType" class="{{ $inputClass }}">
                                    <option value="">What do you need advice on?</option>
                                    @foreach (['Equipment Procurement & Selection', 'Supply Chain Audit & Optimisation', 'Setting Up a New Clinic/Facility', 'Dialysis Supply Planning', 'General Healthcare Supply Query'] as $opt)
                                        <option>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Preferred Format</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ([['physical', '🏥', 'Physical Visit', 'We come to your location'], ['virtual', '💻', 'Virtual', 'WhatsApp or video call']] as [$id, $icon, $label, $sub])
                                        <div wire:click="$set('format', '{{ $id }}')" class="relative border rounded-lg p-3.5 flex items-center gap-3 cursor-pointer transition-colors {{ $format === $id ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                            <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                                            <div><h4 class="text-sm font-semibold text-slate-900 mb-0.5">{{ $label }}</h4><p class="text-[11px] text-slate-500">{{ $sub }}</p></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Additional Notes <span class="font-normal text-slate-400">(optional)</span></label>
                            <textarea wire:model="notes" rows="3" placeholder="Any extra context that would help us prepare..." class="{{ $inputClass }}"></textarea>
                        </div>

                        <div class="flex justify-end items-center mt-7 pt-6 border-t border-slate-100">
                            <button wire:click="next" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-cadical-500 text-white rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors">Continue → Your Details</button>
                        </div>
                    @endif

                    {{-- Step 2: Details --}}
                    @if ($step === 2)
                        <p class="font-bold text-xl text-slate-900 mb-1.5">Your Details</p>
                        <p class="text-sm text-slate-500 mb-7">Tell us who you are so we can prepare properly.</p>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">You are booking as *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ([['individual', '👤', 'Individual', 'Personal or home care'], ['institution', '🏥', 'Institution', 'Hospital, clinic or facility']] as [$id, $icon, $label, $sub])
                                    <div wire:click="$set('callerType', '{{ $id }}')" class="relative border rounded-lg p-3.5 flex items-center gap-2.5 cursor-pointer transition-colors {{ $callerType === $id ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                        <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                                        <div><h4 class="text-sm font-semibold text-slate-900 mb-0.5">{{ $label }}</h4><p class="text-[11px] text-slate-500">{{ $sub }}</p></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">First Name *</label><input wire:model="firstName" placeholder="First name" class="{{ $inputClass }}"></div>
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Last Name *</label><input wire:model="lastName" placeholder="Last name" class="{{ $inputClass }}"></div>
                        </div>

                        @if ($callerType === 'institution')
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Organisation / Facility Name *</label><input wire:model="orgName" placeholder="e.g. Lagos General Hospital" class="{{ $inputClass }}"></div>
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Role / Position *</label><input wire:model="role" placeholder="e.g. Procurement Officer, Head Nurse" class="{{ $inputClass }}"></div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number *</label><input type="tel" wire:model="phone" placeholder="+234 000 000 0000" class="{{ $inputClass }}"></div>
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address *</label><input type="email" wire:model="email" placeholder="your@email.com" class="{{ $inputClass }}"></div>
                        </div>

                        <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Location / Address *</label><input wire:model="location" placeholder="City, area or full address for physical visits" class="{{ $inputClass }}"></div>

                        <div class="flex justify-between items-center mt-7 pt-6 border-t border-slate-100">
                            <button wire:click="back" class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-slate-200 text-slate-500 rounded-lg text-sm font-semibold hover:border-cadical-500 hover:text-cadical-500 transition-colors">← Back</button>
                            <button wire:click="next" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-cadical-500 text-white rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors">Continue → Date & Time</button>
                        </div>
                    @endif

                    {{-- Step 3: Date/Time --}}
                    @if ($step === 3)
                        <p class="font-bold text-xl text-slate-900 mb-1.5">When works for you?</p>
                        <p class="text-sm text-slate-500 mb-7">Pick a slot or request a callback and we'll confirm a time that suits both sides.</p>

                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">How would you prefer to book?</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ([['slot', '📅', 'Pick a Slot', 'Choose a date and time now'], ['callback', '📞', 'Request Callback', 'We call you to confirm timing']] as [$id, $icon, $label, $sub])
                                    <div wire:click="$set('bookingType', '{{ $id }}')" class="relative border rounded-lg p-3.5 flex items-center gap-3 cursor-pointer transition-colors {{ $bookingType === $id ? 'border-cadical-500 bg-blue-50' : 'border-slate-200 hover:border-cadical-500' }}">
                                        <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                                        <div><h4 class="text-sm font-semibold text-slate-900 mb-0.5">{{ $label }}</h4><p class="text-[11px] text-slate-500">{{ $sub }}</p></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if ($bookingType === 'slot')
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Preferred Date *</label><input type="date" min="{{ now()->toDateString() }}" wire:model="prefDate" class="{{ $inputClass }}"></div>
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Available Time Slots</label>
                                <div class="grid grid-cols-3 gap-2 mt-2">
                                    @foreach ([['8:00 AM', true], ['9:00 AM', true], ['10:00 AM', false], ['11:00 AM', true], ['12:00 PM', false], ['1:00 PM', true], ['2:00 PM', true], ['3:00 PM', true], ['4:00 PM', false]] as [$slot, $available])
                                        @if ($available)
                                            <div wire:click="$set('selectedSlot', '{{ $slot }}')" class="border rounded-lg px-2 py-2.5 text-center text-xs font-medium cursor-pointer transition-colors {{ $selectedSlot === $slot ? 'bg-cadical-500 border-cadical-500 text-white font-semibold' : 'border-slate-200 text-slate-700 hover:border-cadical-500' }}">{{ $slot }}</div>
                                        @else
                                            <div class="border border-slate-100 bg-slate-50 text-slate-300 rounded-lg px-2 py-2.5 text-center text-xs font-medium">{{ $slot }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($bookingType === 'callback')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Best Date to Call *</label><input type="date" min="{{ now()->toDateString() }}" wire:model="callbackDate" class="{{ $inputClass }}"></div>
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Preferred Call Window *</label>
                                    <select wire:model="callWindow" class="{{ $inputClass }}">
                                        <option value="">Select a time window</option>
                                        @foreach (['Morning — 8am to 12pm', 'Afternoon — 12pm to 4pm', 'Evening — 4pm to 6pm', 'Anytime during business hours'] as $opt)
                                            <option>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4"><label class="block text-xs font-semibold text-slate-700 mb-1.5">Best Number to Call *</label><input type="tel" wire:model="callbackPhone" placeholder="Phone number for callback" class="{{ $inputClass }}"></div>
                        @endif

                        <div class="flex justify-between items-center mt-7 pt-6 border-t border-slate-100">
                            <button wire:click="back" class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-slate-200 text-slate-500 rounded-lg text-sm font-semibold hover:border-cadical-500 hover:text-cadical-500 transition-colors">← Back</button>
                            <button wire:click="next" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-cadical-500 text-white rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors">Review Booking →</button>
                        </div>
                    @endif

                    {{-- Step 4: Review --}}
                    @if ($step === 4)
                        <p class="font-bold text-xl text-slate-900 mb-1.5">Review & Confirm</p>
                        <p class="text-sm text-slate-500 mb-7">Check everything looks right before submitting.</p>

                        <div class="bg-slate-50 rounded-lg p-5 mb-6 border border-slate-200">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3.5">Booking Summary</h4>
                            @foreach ([['Service', $this->summary['service']], ['Type', $this->summary['type']], ['Name', $this->summary['name']], ['Contact', $this->summary['contact']], ['Location', $this->summary['location']], ['Timing', $this->summary['timing']], ['Notes', $this->summary['notes']]] as [$label, $value])
                                <div class="flex justify-between items-start py-2 border-b border-slate-100 gap-4 last:border-b-0">
                                    <span class="text-sm text-slate-500 flex-shrink-0">{{ $label }}</span>
                                    <span class="text-sm text-slate-900 font-medium text-right">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex gap-2.5 items-start bg-amber-50 border border-amber-200 rounded-lg px-4 py-3.5 mb-5">
                            <span class="text-lg flex-shrink-0">ℹ️</span>
                            <p class="text-xs text-amber-800 leading-relaxed">By submitting this booking you agree to be contacted by the Cadical Solutions team to confirm or adjust your appointment. We respond within 24 hours.</p>
                        </div>

                        <div class="flex justify-between items-center mt-7 pt-6 border-t border-slate-100">
                            <button wire:click="back" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-slate-200 text-slate-500 rounded-lg text-sm font-semibold hover:border-cadical-500 hover:text-cadical-500 transition-colors disabled:opacity-50">← Back</button>
                            <button wire:click="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-colors disabled:opacity-70">
                                <span wire:loading.remove>✓ Confirm Booking</span>
                                <span wire:loading>Submitting…</span>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-5">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="bg-cadical-500 px-5 py-4 text-white text-sm font-bold">What to Expect</div>
                <div class="p-5">
                    @foreach ([
                        ['⏱️', '24hr Confirmation', 'We confirm every booking within 24 hours — sooner for urgent requests'],
                        ['📍', 'We Come to You', 'Physical services are delivered at your facility or location across Nigeria'],
                        ['💻', 'Virtual Available', 'Consultations can be done via WhatsApp video or any preferred platform'],
                        ['🎁', 'Free First Consultation', 'Your first supply consultation is completely free — no strings attached'],
                        ['📋', 'Maintenance Contracts', 'Ask about quarterly contracts for ongoing equipment servicing'],
                    ] as $i => [$icon, $title, $desc])
                        <div class="flex gap-3 py-2.5 items-start {{ $i < 4 ? 'border-b border-slate-100' : '' }}">
                            <span class="text-lg flex-shrink-0 mt-0.5">{{ $icon }}</span>
                            <div><h4 class="text-sm font-semibold text-slate-900 mb-0.5">{{ $title }}</h4><p class="text-xs text-slate-500">{{ $desc }}</p></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="bg-cadical-500 px-5 py-4 text-white text-sm font-bold">Prefer to Call?</div>
                <div class="p-5">
                    @foreach ([
                        ['📞', 'Call Us', '+234 707 617 5550', 'tel:+2347076175550'],
                        ['💬', 'WhatsApp', 'Message us directly for fastest response', 'https://wa.me/2347076175550'],
                        ['✉️', 'Email', 'services@cadical.com', 'mailto:services@cadical.com'],
                    ] as $i => [$icon, $title, $desc, $href])
                        <a href="{{ $href }}" class="flex items-center gap-3 py-2.5 {{ $i < 2 ? 'border-b border-slate-100' : '' }}">
                            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center text-base flex-shrink-0">{{ $icon }}</div>
                            <div><h4 class="text-sm font-semibold text-slate-900 mb-0.5">{{ $title }}</h4><p class="text-xs text-slate-500">{{ $desc }}</p></div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-2.5 items-start bg-amber-50 border border-amber-200 rounded-lg px-4 py-3.5">
                <span class="text-lg flex-shrink-0">🚨</span>
                <p class="text-xs text-amber-800 leading-relaxed"><strong>Equipment emergency?</strong> Don't use this form — call us directly on <strong>+234 707 617 5550</strong> for same-day response.</p>
            </div>
        </div>
    </div>
</section>
