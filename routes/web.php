<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TrackController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/products', fn () => view('products'))->name('products');

Route::get('/cart', fn () => view('cart'))->name('cart');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', fn () => view('checkout'))->name('checkout');
    Route::post('/checkout/verify', [PaymentController::class, 'verify'])->name('checkout.verify');
});

Route::get('/booking', fn () => view('booking'))->name('booking');
Route::get('/service-booking', fn () => view('service-booking'))->name('service-booking');

Route::get('/track', fn () => view('track'))->name('track');
Route::get('/api/track/{code}', [TrackController::class, 'show']);

Route::get('/rfq', fn () => view('rfq'))->name('rfq');
Route::get('/referrals', fn () => view('referrals'))->name('referrals');
Route::get('/institutional-portal', fn () => view('institutional-portal'))->name('institutional-portal');

Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/services/{slug}', [ServicesController::class, 'show'])->name('services.show');

Route::get('/about', fn () => view('about'))->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::get('/terms', fn () => view('terms'))->name('terms');
Route::get('/privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');

// ── Auth ─────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register')->middleware('guest');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');
});

// ── Authenticated dashboard (role redirect target) ─────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect(request()->user()->redirectPath()))->name('dashboard');
});

// ── Role-gated portal roots (full pages built out in Phases 2-4) ──────────
Route::middleware(['auth', 'role:'.User::ROLE_SUPER_ADMIN.','.User::ROLE_ADMIN])->prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => 'Admin dashboard — Phase 3');
});

Route::middleware(['auth', 'role:'.User::ROLE_TECHNICIAN])->prefix('technician')->group(function () {
    Route::get('/jobs', fn () => 'Technician job board — Phase 4');
});

Route::middleware(['auth', 'role:'.User::ROLE_SUPPLIER.','.User::ROLE_VENDOR])->prefix('supplier')->group(function () {
    Route::get('/dashboard', fn () => 'Supplier dashboard — Phase 4');
});
