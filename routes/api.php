<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('throttle:api')->group( function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
        // Route::post('/forgot-password')->name('password.email');
        // Route::post('/reset-password')->name('password.store');
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
        // Route::get('verify/{id}/{hash}')->name('verification.verify');
        // Route::post('/email/verification-notification')->name('verification.send');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
    Route::get('/user', [UserController::class, 'index'])->name('user');
});