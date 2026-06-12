<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\DashboardController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');