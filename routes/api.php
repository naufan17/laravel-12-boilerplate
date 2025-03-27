<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function (): void {
    Route::group(['middleware' => ['guest']], function (): void {
        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
        // Route::post('/forgot-password')->name('password.email');
        // Route::post('/reset-password')->name('password.store');
    });

    Route::group(['middleware' => ['auth:sanctum']], function (): void {
        // Route::get('verify/{id}/{hash}')->name('verification.verify');
        // Route::post('/email/verification-notification')->name('verification.send');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});