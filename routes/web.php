<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

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
