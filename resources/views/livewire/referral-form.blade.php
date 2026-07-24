@php
    $inputClass = 'w-full px-4 py-2.5 sm:py-3 border-2 border-gray-200 rounded-lg focus:border-[#1B3A5C] focus:outline-none transition-all bg-blue-50';
    $steps = ['Referrer' => '🏥', 'Client' => '👤', 'Supply' => '🧪', 'Confirm' => '🔗'];
@endphp
<div class="min-h-screen bg-gradient-to-b from-blue-500 via-blue-600 to-cadical-500 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        {{-- Banner --}}
        <div class="relative rounded-t-lg overflow-hidden bg-gradient-to-r from-cadical-500 via-blue-600 to-blue-700 px-6 sm:px-8 py-8 sm:py-10 shadow-lg">
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center shadow-lg">
                        <img src="{{ \App\Models\HomeSection::mediaUrl(config('site.logo')) ?: asset('images/logo.png') }}" alt="Cadical" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-semibold text-white">Cadical Solutions Ltd</h1>
                        <p class="text-xs sm:text-sm text-blue-200 opacity-75 tracking-wide mt-1">www.cadical.com &bull; Healthcare Supplies</p>
                    </div>
                </div>
                <div class="hidden sm:block text-right">
                    <div class="inline-block bg-yellow-500 text-[#1B3A5C] text-xs font-bold px-3 py-1 rounded-full mb-2 tracking-wider">Official Form</div>
                    <h2 class="text-2xl font-semibold text-white">Supply & Services Request Form</h2>
                    <p class="text-xs sm:text-sm text-blue-200 opacity-75 mt-1">Nigeria's Healthcare Supply Partner</p>
                </div>
            </div>
        </div>

        {{-- Progress --}}
        <div class="bg-gradient-to-r from-cadical-500 to-[#1B3A5C] px-6 sm:px-8 py-6 sm:py-8">
            <div class="relative flex justify-between">
                <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-cadical-500 opacity-20 -translate-y-1/2"></div>
                @foreach ($steps as $label => $icon)
                    @php $idx = $loop->index; @endphp
                    <div class="relative flex flex-col items-center z-10">
                        <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-full border-2 flex items-center justify-center text-xs sm:text-sm font-bold
                            {{ $currentStep > $idx ? 'bg-emerald-600 border-emerald-400 text-white' : ($currentStep === $idx ? 'bg-yellow-500 border-yellow-500 text-[#1B3A5C]' : 'bg-white/15 border-blue-300 text-blue-200') }}">
                            {{ $currentStep > $idx ? '✓' : $idx + 1 }}
                        </div>
                        <p class="text-xs sm:text-sm mt-2 text-center font-medium tracking-wider {{ $currentStep >= $idx ? 'text-yellow-400' : 'text-blue-300' }}">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Form card --}}
        <div class="bg-white px-6 sm:px-8 py-8 sm:py-10 shadow-lg rounded-b-lg">
            @if ($submitted)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-8 sm:p-12 text-center">
                    <div class="text-5xl sm:text-6xl mb-4">✅</div>
                    <h3 class="text-2xl sm:text-3xl font-semibold text-green-700 mb-2">Supply Request Submitted!</h3>
                    <p class="text-green-600 mb-4">Your supply request has been received by Cadical Solutions Ltd. A confirmation will be sent to your email or phone shortly.</p>
                    <p class="font-bold text-green-700 mb-6">Order Reference ID: <span class="text-lg text-green-600">{{ $refId }}</span></p>
                    <button wire:click="resetForm" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-all shadow-lg">Submit Another Request</button>
                </div>
            @else
                <form wire:submit="submit" class="space-y-8">
                    <div class="bg-blue-50 border-l-4 border-[#1B3A5C] rounded-r-lg p-4 sm:p-5">
                        <p class="text-sm sm:text-base text-[#1B3A5C] leading-relaxed">
                            <strong>Important:</strong> This form is for healthcare professionals, facilities, and affiliate partners referring clients or institutions to Cadical Solutions Ltd for healthcare supplies and related services. All fields marked <span class="text-red-600">*</span> are required. A unique Order Reference ID will be generated upon submission.
                        </p>
                    </div>

                    {{-- STEP 1 --}}
                    @if ($currentStep === 0)
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">🏥</div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-semibold text-[#1B3A5C]">Section 1 — Contact / Ordering Party</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Details of the healthcare professional, facility, or affiliate partner placing this supply request</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Full Name <span class="text-red-600">*</span></label><input wire:model="referrerFullName" placeholder="e.g. Mr. Isaac Okondi Yohanna" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Designation / Role <span class="text-red-600">*</span></label>
                                    <select wire:model="referrerDesignation" class="{{ $inputClass }}">
                                        <option value="">— Select Role —</option>
                                        @foreach (['Medical Laboratory Technician', 'Medical Laboratory Scientist', 'Medical Doctor (GP)', 'Pharmacist', 'Nurse / Midwife', 'Community Health Officer (CHO)', 'Community Health Extension Worker (CHEW)', 'Hospital Administrator', 'Affiliate Marketer', 'Other'] as $opt)
                                            <option>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Facility / Organisation Name <span class="text-red-600">*</span></label><input wire:model="referrerFacility" placeholder="e.g. Kaduna State PHC Development Agency" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Facility Type <span class="text-red-600">*</span></label>
                                    <select wire:model="referrerFacilityType" class="{{ $inputClass }}">
                                        <option value="">— Select Type —</option>
                                        @foreach (['Primary Health Centre (PHC)', 'General Hospital', 'Teaching Hospital', 'Private Hospital / Clinic', 'Medical Laboratory', 'Pharmacy', 'NGO / Faith-Based Organisation', 'Individual Practice', 'Other'] as $opt)
                                            <option>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Phone Number <span class="text-red-600">*</span></label><input type="tel" wire:model="referrerPhone" placeholder="e.g. 08012345678" class="{{ $inputClass }}"></div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Email Address</label><input type="email" wire:model="referrerEmail" placeholder="e.g. yourname@email.com" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">State <span class="text-red-600">*</span></label>
                                    <select wire:model="referrerState" class="{{ $inputClass }}">
                                        @foreach (\App\Livewire\ReferralForm::STATES as $state)<option>{{ $state }}</option>@endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">LGA <span class="text-red-600">*</span></label><input wire:model="referrerLGA" placeholder="e.g. Kaura LGA" class="{{ $inputClass }}"></div>
                                <div class="sm:col-span-2"><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Full Address</label><input wire:model="referrerAddress" placeholder="Street, Town / City" class="{{ $inputClass }}"></div>
                            </div>
                        </div>
                    @endif

                    {{-- STEP 2 --}}
                    @if ($currentStep === 1)
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">👤</div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-semibold text-[#1B3A5C]">Section 2 — Client / Facility Details</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Details of the client, facility, or institution requesting Cadical's supplies or services</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Client / Facility Name <span class="text-red-600">*</span></label><input wire:model="clientFacilityName" placeholder="e.g. Kagoro General Hospital or Mr. John Doe" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Client Type <span class="text-red-600">*</span></label>
                                    <select wire:model="clientType" class="{{ $inputClass }}">
                                        <option value="">— Select —</option>
                                        @foreach (['Private Hospital / Clinic', 'Government Hospital', 'Primary Health Centre (PHC)', 'Medical / Diagnostic Laboratory', 'Pharmacy / Drug Store', 'Individual Healthcare Professional', 'NGO / Faith-Based Organisation', 'Research Institution', 'Other'] as $opt)
                                            <option>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Contact Person</label><input wire:model="clientContactPerson" placeholder="Name of person to contact at the facility" class="{{ $inputClass }}"></div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Contact Phone Number <span class="text-red-600">*</span></label><input type="tel" wire:model="clientPhone" placeholder="08012345678" class="{{ $inputClass }}"></div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Contact Email</label><input type="email" wire:model="clientEmail" placeholder="facility@email.com" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">State <span class="text-red-600">*</span></label>
                                    <select wire:model="clientState" class="{{ $inputClass }}">
                                        @foreach (\App\Livewire\ReferralForm::STATES as $state)<option>{{ $state }}</option>@endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">LGA</label><input wire:model="clientLGA" placeholder="e.g. Kaura LGA" class="{{ $inputClass }}"></div>
                                <div class="sm:col-span-2"><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Client / Facility Address</label><input wire:model="clientAddress" placeholder="Street, Town / City, State" class="{{ $inputClass }}"></div>
                                <div class="sm:col-span-2"><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Reason for Supply Request <span class="text-red-600">*</span></label><textarea wire:model="reasonForRequest" placeholder="Describe what the client or facility needs — e.g. restocking diagnostic kits, setting up a new laboratory, or fulfilling a specific supply requirement..." class="{{ $inputClass }} resize-none min-h-24"></textarea></div>
                            </div>
                        </div>
                    @endif

                    {{-- STEP 3 --}}
                    @if ($currentStep === 2)
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">🧪</div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-semibold text-[#1B3A5C]">Section 3 — Supply & Product Request</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Specify the healthcare supplies, diagnostic products, or services required</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-4">Supply Category <span class="text-red-600">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach (\App\Livewire\ReferralForm::CATEGORIES as $cat)
                                        <button type="button" wire:click="toggleCategory('{{ $cat['name'] }}')" class="p-4 rounded-lg border-2 text-left transition-all {{ in_array($cat['name'], $supplyCategory) ? 'border-[#1B3A5C] bg-blue-100' : 'border-gray-200 bg-blue-50 hover:border-[#1B3A5C]' }}">
                                            <div class="text-2xl mb-2">{{ $cat['icon'] }}</div>
                                            <div class="font-bold text-sm sm:text-base text-[#1B3A5C]">{{ $cat['name'] }}</div>
                                            <div class="text-xs sm:text-sm text-gray-600 mt-1">{{ $cat['desc'] }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-4">Specific Tests / Products Required</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach (\App\Livewire\ReferralForm::TESTS as $test)
                                        <label class="flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all {{ in_array($test, $specificTests) ? 'border-[#1B3A5C] bg-blue-100' : 'border-gray-200 bg-blue-50 hover:border-[#1B3A5C]' }}">
                                            <input type="checkbox" wire:click="toggleTest('{{ $test }}')" @checked(in_array($test, $specificTests)) class="w-4 h-4 accent-[#1B3A5C] cursor-pointer">
                                            <span class="text-sm">{{ $test }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-4">Urgency Level <span class="text-red-600">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    @foreach ([['routine', '✅ Routine', 'bg-green-50 border-green-400'], ['urgent', '⚠️ Urgent (24–48hrs)', 'bg-yellow-50 border-yellow-400'], ['emergency', '🚨 Emergency (Same Day)', 'bg-red-50 border-red-400']] as [$val, $label, $color])
                                        <button type="button" wire:click="$set('urgencyLevel', '{{ $val }}')" class="py-3 px-4 rounded-lg border-2 font-semibold transition-all {{ $urgencyLevel === $val ? $color : 'border-gray-200 bg-blue-50' }}">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Quantity / Volume Required</label><input wire:model="quantity" placeholder="e.g. 2 kits, 100 tests, 5 boxes" class="{{ $inputClass }}"></div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Preferred Delivery Method</label>
                                    <select wire:model="deliveryMethod" class="{{ $inputClass }}">
                                        <option value="">— Select —</option>
                                        @foreach (['Pickup from Cadical Office', 'Courier Delivery', 'State Distributor / Agent', 'Dispatch Rider'] as $opt)<option>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-2"><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Additional Notes / Special Instructions</label><textarea wire:model="additionalNotes" placeholder="Brand preferences, cold chain requirements, delivery instructions, alternative products, or any other supply-related notes..." class="{{ $inputClass }} resize-none min-h-24"></textarea></div>
                            </div>
                        </div>
                    @endif

                    {{-- STEP 4 --}}
                    @if ($currentStep === 3)
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">🔗</div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-semibold text-[#1B3A5C]">Section 4 — Supply Referral & Affiliate Tracking</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">For affiliate partners referring clients to Cadical — commission tracking and order attribution</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Affiliate / Referral ID</label>
                                    <input wire:model="affiliateId" placeholder="e.g. AFF-KD-001" class="{{ $inputClass }}">
                                    <p class="text-xs text-gray-500 mt-2">Your unique Cadical affiliate link ID</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Referred Via</label>
                                    <select wire:model="referredVia" class="{{ $inputClass }}">
                                        <option value="">— Select Channel —</option>
                                        @foreach (['Direct Visit (www.cadical.com)', 'WhatsApp Referral', 'SMS / Phone Call', 'Community Outreach', 'Hospital Network', 'Social Media', 'Word of Mouth'] as $opt)<option>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Payment Preference</label>
                                    <select wire:model="paymentPreference" class="{{ $inputClass }}">
                                        <option value="">— Select —</option>
                                        @foreach (['Bank Transfer', 'POS / Card Payment', 'USSD Payment', 'Cash on Delivery', 'Institutional Purchase Order', 'Monthly Supply Agreement'] as $opt)<option>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div><label class="block text-xs sm:text-sm font-bold text-[#1B3A5C] uppercase tracking-wider mb-2">Estimated Order Value (&#8358;)</label><input wire:model="estimatedValue" placeholder="e.g. 50,000" class="{{ $inputClass }}"></div>
                            </div>

                            <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-5 sm:p-6">
                                <label class="flex items-start gap-4 cursor-pointer">
                                    <input type="checkbox" wire:model="consent" class="w-5 h-5 accent-yellow-600 mt-1 cursor-pointer flex-shrink-0">
                                    <span class="text-sm sm:text-base text-yellow-900 leading-relaxed">
                                        I confirm that the information provided in this form is accurate to the best of my knowledge. I consent to Cadical Solutions Ltd processing this supply request and contacting the client or facility on my behalf. I acknowledge that this request is subject to Cadical's
                                        <a href="{{ url('/terms') }}" target="_blank" class="underline font-semibold text-red-600 hover:text-red-700">Terms of Service</a> and
                                        <a href="{{ url('/privacy-policy') }}" target="_blank" class="underline font-semibold text-red-600 hover:text-red-700">Privacy Policy</a>.
                                        Data is protected under the Nigeria Data Protection Act (NDPA).
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif

                    {{-- Nav --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-between pt-6 border-t border-gray-200">
                        <button type="button" wire:click="prev" @disabled($currentStep === 0)
                            class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold transition-all {{ $currentStep === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white border-2 border-gray-300 text-gray-700 hover:border-[#1B3A5C] hover:text-[#1B3A5C]' }}">
                            ← Previous
                        </button>

                        <div class="flex gap-3 sm:gap-4 flex-1 sm:flex-initial justify-center sm:justify-end">
                            @if ($currentStep === 3)
                                <button type="button" wire:click="resetForm" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold bg-white border-2 border-gray-300 text-gray-700 hover:border-gray-500 transition-all">Clear Form</button>
                                <button type="submit" wire:loading.attr="disabled" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold bg-[#1B3A5C] text-white hover:bg-[#142e4a] transition-all shadow-lg flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span wire:loading.remove>✓ Submit Request</span>
                                    <span wire:loading>⏳ Submitting…</span>
                                </button>
                            @else
                                <button type="button" wire:click="next" class="px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold bg-[#1B3A5C] text-white hover:bg-[#142e4a] transition-all shadow-lg">Next →</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
