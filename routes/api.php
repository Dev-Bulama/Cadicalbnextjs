<?php

use Illuminate\Support\Facades\Route;

// Technician mobile / PWA API surface (Sanctum bearer tokens) is built out in Phase 4/5.
Route::middleware('auth:sanctum')->get('/user', function () {
    return request()->user();
});
