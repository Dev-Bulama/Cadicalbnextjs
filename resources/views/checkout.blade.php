@extends('layouts.app')

@section('head')
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
@endsection

@section('content')
@php
    $user = auth()->user();
@endphp
<div
    x-data="checkoutPage({
        publicKeyFlutterwave: {{ Illuminate\Support\Js::from(config('services.flutterwave.public_key')) }},
        publicKeyPaystack: {{ Illuminate\Support\Js::from(config('services.paystack.public_key')) }},
        userEmail: {{ Illuminate\Support\Js::from($user->email ?? '') }},
        userName: {{ Illuminate\Support\Js::from($user->name ?? '') }},
        isGuest: {{ Illuminate\Support\Js::from(! $user) }},
        verifyUrl: {{ Illuminate\Support\Js::from(url('/checkout/verify')) }},
        csrfToken: {{ Illuminate\Support\Js::from(csrf_token()) }},
    })"
    class="min-h-screen flex flex-col bg-slate-50 pt-16"
>
    @guest
        <div class="bg-cadical-500/5 border-b border-cadical-500/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-slate-600">Checking out as a guest. <span class="hidden sm:inline">Create an account to track orders and check out faster next time.</span></p>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ url('/auth/login') }}" class="text-sm font-semibold text-cadical-500 hover:text-cadical-700 px-3 py-1.5">Log In</a>
                    <a href="{{ url('/auth/register') }}" class="text-sm font-semibold bg-cadical-500 hover:bg-cadical-700 text-white px-3 py-1.5 rounded-lg">Create Account</a>
                </div>
            </div>
        </div>
    @endguest

    {{-- Progress steps --}}
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <template x-for="(s, idx) in ['Shipping', 'Payment', 'Confirmation']" :key="s">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center font-bold transition-all"
                            :class="idx < stepIndex ? 'bg-cadical-500 text-white' : idx === stepIndex ? 'bg-cadical-500 text-white ring-4 ring-cadical-500/30' : 'bg-slate-100 text-slate-400'">
                            <span x-text="idx + 1"></span>
                        </div>
                        <span class="font-semibold hidden sm:block" :class="idx <= stepIndex ? 'text-cadical-500' : 'text-slate-400'" x-text="s"></span>
                        <div class="h-1 w-12 bg-slate-200" x-show="idx < 2"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">

                {{-- Shipping --}}
                <template x-if="step === 'shipping'">
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <i data-lucide="truck" class="w-6 h-6 text-cadical-500"></i>
                            <h2 class="text-2xl font-bold text-slate-900">Shipping Address</h2>
                        </div>

                        <form @submit.prevent="submitShipping" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2">First Name *</label>
                                    <input x-model="form.firstName" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Last Name *</label>
                                    <input x-model="form.lastName" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Email <span x-show="isGuest">*</span></label>
                                    <input type="email" x-model="form.email" :required="isGuest" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Phone</label>
                                    <input type="tel" x-model="form.phone" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Address *</label>
                                <input x-model="form.address" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-2">City *</label>
                                    <input x-model="form.city" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">State</label>
                                    <input x-model="form.state" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-2">Zip Code</label>
                                    <input x-model="form.zipCode" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Country</label>
                                <select x-model="form.country" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm">
                                    <option value="">Select country</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="KE">Kenya</option>
                                    <option value="GH">Ghana</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-cadical-500 hover:bg-cadical-700 text-white font-semibold py-3 rounded-xl text-sm transition-colors">Continue to Payment</button>
                        </form>
                    </div>
                </template>

                {{-- Payment --}}
                <template x-if="step === 'payment'">
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 space-y-6">
                        <div class="flex items-center gap-3 mb-2">
                            <i data-lucide="credit-card" class="w-6 h-6 text-cadical-500"></i>
                            <h2 class="text-2xl font-bold text-slate-900">Payment Method</h2>
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition-colors" :class="gateway === 'flutterwave' ? 'border-cadical-500 bg-cadical-500/5' : 'border-slate-200'">
                                <input type="radio" x-model="gateway" value="flutterwave" class="w-4 h-4 accent-cadical-500">
                                <div>
                                    <p class="font-semibold text-slate-900">Flutterwave</p>
                                    <p class="text-sm text-slate-500">Card, bank transfer, USSD, mobile money</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition-colors" :class="gateway === 'paystack' ? 'border-cadical-500 bg-cadical-500/5' : 'border-slate-200'">
                                <input type="radio" x-model="gateway" value="paystack" class="w-4 h-4 accent-cadical-500">
                                <div>
                                    <p class="font-semibold text-slate-900">Paystack</p>
                                    <p class="text-sm text-slate-500">Card, bank transfer, USSD</p>
                                </div>
                            </label>
                        </div>

                        <div class="bg-cadical-500/10 border border-cadical-500/20 rounded-lg p-4 flex items-start gap-3">
                            <i data-lucide="lock" class="w-5 h-5 text-cadical-500 flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm">
                                <p class="font-semibold text-slate-900 mb-1">Secure Payment</p>
                                <p class="text-slate-500">Your payment information is encrypted and secure. We never store your card details.</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button @click="pay" :disabled="isProcessing" class="w-full flex items-center justify-center gap-2 bg-cadical-500 hover:bg-cadical-700 disabled:bg-slate-200 disabled:text-slate-400 text-white font-semibold py-3 rounded-xl text-sm transition-colors">
                                <span x-text="isProcessing ? 'Processing…' : 'Complete Purchase'"></span>
                                <i data-lucide="credit-card" class="w-5 h-5" x-show="!isProcessing"></i>
                            </button>
                            <button @click="step = 'shipping'" :disabled="isProcessing" class="w-full flex items-center justify-center gap-2 border border-slate-200 font-semibold py-3 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                                <i data-lucide="arrow-left" class="w-5 h-5"></i> Back to Shipping
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 text-center">Your order will be confirmed after payment</p>
                    </div>
                </template>

                {{-- Confirmation --}}
                <template x-if="step === 'confirmation'">
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center space-y-6">
                        <div class="flex justify-center mb-2">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-pulse"></div>
                                <i data-lucide="check-circle" class="w-16 h-16 text-emerald-500 relative"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <h2 class="text-3xl font-bold text-slate-900">Order Confirmed!</h2>
                            <p class="text-slate-500">Thank you for your purchase. Your order has been confirmed.</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-4 space-y-2 text-left">
                            <div class="flex justify-between"><span class="text-slate-500">Tracking Code:</span><span class="font-bold" x-text="orderResult?.tracking_code"></span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Total Amount:</span><span class="font-bold" x-text="'₦' + Number(orderResult?.total_amount).toLocaleString()"></span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Status:</span><span class="font-bold text-emerald-600">Paid</span></div>
                        </div>
                        <div class="bg-cadical-500/10 border border-cadical-500/20 rounded-lg p-4">
                            <p class="text-sm text-slate-700">A confirmation has been sent to <strong x-text="form.email || 'your email'"></strong></p>
                        </div>
                        <a href="{{ url('/products') }}" class="w-full flex items-center justify-center gap-2 bg-cadical-500 text-white font-semibold py-3 rounded-xl text-sm hover:bg-cadical-700 transition-colors">Continue Shopping</a>
                    </div>
                </template>
            </div>

            {{-- Order summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-slate-900 mb-6">Order Summary</h3>
                    <div class="space-y-3 mb-6 pb-6 border-b border-slate-100 max-h-64 overflow-y-auto">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex justify-between gap-2 text-sm">
                                <span class="text-slate-500" x-text="item.name + ' x' + item.quantity"></span>
                                <span class="font-semibold text-slate-900 flex-shrink-0" x-text="'₦' + (item.price * item.quantity).toLocaleString()"></span>
                            </div>
                        </template>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="text-lg font-bold text-cadical-500" x-text="'₦' + $store.cart.subtotal.toLocaleString()"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function checkoutPage(cfg) {
        return {
            step: 'shipping',
            gateway: 'flutterwave',
            isProcessing: false,
            isGuest: cfg.isGuest,
            orderResult: null,
            form: { firstName: '', lastName: '', email: cfg.userEmail, phone: '', address: '', city: '', state: '', zipCode: '', country: '' },
            get stepIndex() { return ['shipping', 'payment', 'confirmation'].indexOf(this.step) },

            init() {
                if (this.$store.cart.items.length === 0 && this.step !== 'confirmation') {
                    window.location.href = '{{ url('/cart') }}';
                }
            },

            submitShipping() {
                if (!this.form.firstName || !this.form.address || !this.form.city) {
                    this.$dispatch('cart-toast', { message: 'Please fill in all required fields' });
                    return;
                }
                if (this.isGuest && !this.form.email) {
                    this.$dispatch('cart-toast', { message: 'Email is required so we can confirm your order' });
                    return;
                }
                this.step = 'payment';
            },

            async pay() {
                if (this.isProcessing) return;
                this.isProcessing = true;
                const amount = this.$store.cart.subtotal;
                const txRef = 'CAD-' + Date.now();

                try {
                    if (this.gateway === 'flutterwave') {
                        if (typeof FlutterwaveCheckout !== 'function') {
                            throw new Error('Flutterwave checkout failed to load — check your connection and try again.');
                        }
                        if (!cfg.publicKeyFlutterwave) {
                            throw new Error('Flutterwave is not configured yet. Try Paystack, or contact support.');
                        }
                        FlutterwaveCheckout({
                            public_key: cfg.publicKeyFlutterwave,
                            tx_ref: txRef,
                            amount: amount,
                            currency: 'NGN',
                            payment_options: 'card, banktransfer, ussd',
                            customer: { email: this.form.email, phone_number: this.form.phone, name: `${this.form.firstName} ${this.form.lastName}` },
                            customizations: { title: 'Cadical Store', description: 'Payment for medical products', logo: '{{ asset('images/logo.png') }}' },
                            callback: (response) => this.handleGatewayResponse('flutterwave', response.transaction_id),
                            onclose: () => { this.isProcessing = false; this.$dispatch('cart-toast', { message: 'Payment cancelled' }) },
                        });
                    } else {
                        if (typeof PaystackPop === 'undefined') {
                            throw new Error('Paystack checkout failed to load — check your connection and try again.');
                        }
                        if (!cfg.publicKeyPaystack) {
                            throw new Error('Paystack is not configured yet. Try Flutterwave, or contact support.');
                        }
                        const handler = PaystackPop.setup({
                            key: cfg.publicKeyPaystack,
                            email: this.form.email || cfg.userEmail,
                            amount: Math.round(amount * 100),
                            currency: 'NGN',
                            ref: txRef,
                            callback: (response) => this.handleGatewayResponse('paystack', response.reference),
                            onClose: () => { this.isProcessing = false; this.$dispatch('cart-toast', { message: 'Payment cancelled' }) },
                        });
                        handler.openIframe();
                    }
                } catch (e) {
                    this.isProcessing = false;
                    this.$dispatch('cart-toast', { message: e.message || 'Could not open the payment window' });
                }
            },

            async handleGatewayResponse(gateway, reference) {
                if (!reference) {
                    this.isProcessing = false;
                    this.$dispatch('cart-toast', { message: 'No transaction reference returned' });
                    return;
                }
                try {
                    const res = await fetch(cfg.verifyUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': cfg.csrfToken },
                        body: JSON.stringify({
                            gateway,
                            reference: String(reference),
                            cart_items: this.$store.cart.items.map(i => ({ id: i.id, quantity: i.quantity })),
                            shipping_address: `${this.form.address}, ${this.form.city}, ${this.form.state} ${this.form.zipCode}, ${this.form.country}`.trim(),
                            guest_name: cfg.isGuest ? `${this.form.firstName} ${this.form.lastName}`.trim() : undefined,
                            guest_email: cfg.isGuest ? this.form.email : undefined,
                            guest_phone: cfg.isGuest ? this.form.phone : undefined,
                        }),
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        this.orderResult = data.order;
                        this.$store.cart.clearCart();
                        this.step = 'confirmation';
                        this.$dispatch('cart-toast', { message: 'Payment successful!' });
                    } else {
                        this.$dispatch('cart-toast', { message: 'Payment verification failed' });
                    }
                } catch (e) {
                    this.$dispatch('cart-toast', { message: 'Error verifying payment' });
                } finally {
                    this.isProcessing = false;
                }
            },
        }
    }
</script>
@endsection
