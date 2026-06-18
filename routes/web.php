<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NilaiGuruController;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {

        'admin' =>
        redirect()->route('admin.dashboard'),

        'yayasan' =>
        redirect()->route('yayasan.dashboard'),

        'guru' =>
        redirect()->route('guru.dashboard'),

        default =>
        redirect()->route('login'),
    };
});

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

        Route::resource('nilai-guru', NilaiGuruController::class)
            ->except([
                'show',
                'destroy'
            ]);
    });

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');