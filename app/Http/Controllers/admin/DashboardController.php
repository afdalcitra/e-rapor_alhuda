<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalGuru' => User::where('role', 'guru')->count(),
            'guruTidakAktif' => User::where('role', 'guru')->where('status', 'tidak_aktif')->count(),
            'totalYayasan' => User::where('role', 'yayasan')->count(),
            'totalAdmin' => User::where('role', 'admin')->count(),
            'adminTidakAktif' => User::where('role', 'admin')->where('status', 'tidak_aktif')->count(),
            'menungguVerifikasi' => User::where('status', 'menunggu')->count(), // sesuaikan kondisinya
        ]);
    }
}
