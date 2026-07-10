@extends('layouts.app')
@section('content')
<div x-data="{
        couponCode: '', discount: 0,
        applyCoupon() {
            if (this.couponCode.trim().toUpperCase() === 'CADICAL10') { this.discount = 10; this.$dispatch('cart-toast', { message: 'Coupon applied successfully' }) }
            else { this.$dispatch('cart-toast', { message: 'Invalid coupon code' }) }
        },
        get discountAmount() { return $store.cart.subtotal * (this.discount / 100) },
        get tax() { return ($store.cart.subtotal - this.discountAmount) * 0.1 },
        get total() { return $store.cart.subtotal - this.discountAmount + this.tax },
    }"
    class="min-h-screen flex flex-col bg-slate-50 pt-16 pb-24 lg:pb-0">

    <template x-if="$store.cart.items.length === 0">
        <div class="flex-1 flex items-center justify-center px-4 py-24">
            <div class="text-center">
                <i data-lucide="shopping-cart" class="w-16 h-16 mx-auto text-slate-200 mb-6"></i>
                <h1 class="text-3xl font-bold text-slate-900">Your cart is empty</h1>
                <p class="text-slate-500 mt-3 mb-6">Add products to continue</p>
                <a href="{{ url('/products') }}" class="inline-flex items-center gap-2 bg-cadical-500 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-cadical-700 transition-colors">Continue Shopping</a>
            </div>
        </div>
    </template>

    <template x-if="$store.cart.items.length > 0">
        <div>
            <div class="bg-cadical-700 px-4 md:px-8 py-8">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-2xl font-bold text-white">Shopping Cart</h1>
                    <p class="text-white/60 text-sm mt-1"><span x-text="$store.cart.items.length"></span> item<span x-show="$store.cart.items.length !== 1">s</span> in your cart</p>
                </div>
            </div>

            <main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <section class="mt-8 lg:col-span-2 space-y-4">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="border border-slate-200 rounded-2xl p-5 bg-white">
                                <div class="flex justify-between gap-4">
                                    <div>
                                        <h3 class="font-semibold text-lg text-slate-900" x-text="item.name"></h3>
                                        <p class="text-sm text-slate-500" x-text="item.category"></p>
                                        <p class="mt-2 text-cadical-500 font-bold text-xl" x-text="'₦' + (item.price * item.quantity).toLocaleString()"></p>
                                    </div>
                                    <div class="flex flex-col items-end gap-4">
                                        <button @click="$store.cart.removeFromCart(item.id); $dispatch('cart-toast', { message: 'Removed from cart' })" class="text-red-500">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                        <div class="flex items-center gap-3 border border-slate-200 rounded-lg p-2">
                                            <button @click="$store.cart.updateQuantity(item.id, item.quantity - 1)"><i data-lucide="minus" class="w-4 h-4"></i></button>
                                            <span class="w-6 text-center" x-text="item.quantity"></span>
                                            <button @click="$store.cart.updateQuantity(item.id, item.quantity + 1)"><i data-lucide="plus" class="w-4 h-4"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </section>

                    <aside class="mt-8 border border-slate-200 rounded-2xl bg-white p-6 h-fit sticky top-24">
                        <h2 class="text-2xl font-bold mb-6 text-slate-900">Order Summary</h2>

                        <div class="space-y-2 mb-6">
                            <input x-model="couponCode" placeholder="Coupon code" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
                            <button @click="applyCoupon" class="w-full border border-slate-200 rounded-lg py-2 text-sm font-semibold hover:bg-slate-50 transition-colors">Apply Coupon</button>
                        </div>

                        <div class="space-y-4 border-t border-slate-100 pt-4 text-sm">
                            <div class="flex justify-between"><span class="text-slate-600">Subtotal</span><span class="text-slate-900" x-text="'₦' + $store.cart.subtotal.toLocaleString()"></span></div>
                            <template x-if="discount > 0">
                                <div class="flex justify-between text-emerald-600"><span>Discount (<span x-text="discount"></span>%)</span><span x-text="'-₦' + discountAmount.toLocaleString()"></span></div>
                            </template>
                            <div class="flex justify-between"><span class="text-slate-600">Tax</span><span class="text-slate-900" x-text="'₦' + tax.toLocaleString()"></span></div>
                            <div class="border-t border-slate-100 pt-4 flex justify-between text-lg font-bold text-slate-900"><span>Total</span><span x-text="'₦' + total.toLocaleString()"></span></div>
                        </div>

                        <a href="{{ url('/checkout') }}" class="mt-6 w-full flex items-center justify-center gap-2 bg-cadical-500 text-white py-3 rounded-xl font-semibold text-sm hover:bg-cadical-700 transition-colors">
                            Checkout <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </aside>
                </div>
            </main>
        </div>
    </template>
</div>
@endsection
