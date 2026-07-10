<?php

use App\Http\Controllers\Clinician\DashboardController;
use App\Http\Controllers\Clinician\OpportunitiesController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.User::ROLE_CLINICIAN])->prefix('clinician')->name('clinician.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/opportunities', [OpportunitiesController::class, 'index'])->name('opportunities');
    Route::get('/profile', fn () => view('clinician.profile'))->name('profile');
});
