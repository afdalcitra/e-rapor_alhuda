<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'index'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'role:admin'
    ])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', UserController::class)
            ->except([
                'show',
                'destroy',
            ]);
    });

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');