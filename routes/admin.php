<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.User::ROLE_SUPER_ADMIN.','.User::ROLE_ADMIN])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', fn () => view('admin.products'))->name('products');
    Route::get('/orders', fn () => view('admin.orders'))->name('orders');
    Route::get('/bookings', fn () => view('admin.bookings'))->name('bookings');
    Route::get('/clinicians', fn () => view('admin.clinicians'))->name('clinicians');
    Route::get('/referrals', fn () => view('admin.referrals'))->name('referrals');
    Route::get('/institutions', fn () => view('admin.institutions'))->name('institutions');
    Route::get('/suppliers', fn () => view('admin.suppliers'))->name('suppliers');
    Route::get('/rfq', fn () => view('admin.rfq'))->name('rfq');
    Route::get('/service-jobs', fn () => view('admin.service-jobs'))->name('service-jobs');
    Route::get('/technicians', fn () => view('admin.technicians'))->name('technicians');
    Route::get('/maintenance', fn () => view('admin.maintenance'))->name('maintenance');
    Route::get('/services', fn () => view('admin.services'))->name('services');
    Route::get('/integrations/crm', [CrmController::class, 'index'])->name('integrations.crm');
    Route::get('/tracking', fn () => view('admin.tracking'))->name('tracking');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/audit-logs', fn () => view('admin.audit-logs'))->name('audit-logs');
});
