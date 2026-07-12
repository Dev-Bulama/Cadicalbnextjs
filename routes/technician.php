<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.User::ROLE_TECHNICIAN])->prefix('technician')->name('technician.')->group(function () {
    Route::get('/', fn () => redirect()->route('technician.jobs'));
    Route::get('/jobs', fn () => view('technician.jobs'))->name('jobs');
    Route::get('/schedule', fn () => view('technician.schedule'))->name('schedule');
    Route::get('/tracking', fn () => view('technician.tracking'))->name('tracking');
    Route::get('/notifications', fn () => view('technician.notifications'))->name('notifications');
    Route::get('/profile', fn () => view('technician.profile'))->name('profile');
});
