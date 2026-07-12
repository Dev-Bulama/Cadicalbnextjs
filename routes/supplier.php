<?php

use App\Http\Controllers\Supplier\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/supplier/register', fn () => view('supplier.register'))->name('supplier.register');

Route::middleware(['auth', 'role:'.User::ROLE_SUPPLIER.','.User::ROLE_VENDOR])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/', fn () => redirect()->route('supplier.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', fn () => view('supplier.products'))->name('products');
    Route::get('/orders', fn () => view('supplier.orders'))->name('orders');
    Route::get('/profile', fn () => view('supplier.profile'))->name('profile');
});
