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

        // PENTING: route ini harus didaftarkan SEBELUM Route::resource('nilai-guru', ...)
        // supaya tidak konflik dengan path /nilai-guru/{nilaiGuru} milik resource.
        // Nama route TIDAK perlu diawali "admin." lagi karena group di atas
        // sudah otomatis menambahkan prefix nama "admin." untuk semua route di dalamnya.
        Route::post('/nilai-guru/set-periode-aktif', [NilaiGuruController::class, 'setPeriodeAktif'])
            ->name('nilai-guru.set-periode-aktif');

        Route::resource('nilai-guru', NilaiGuruController::class)
            ->except([
                'show',
                'destroy',
            ]);
    });

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');