<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NilaiGuru;
use App\Models\PeriodeAktif;

class DashboardController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeAktif::aktif();

        $guruAktif = User::where('role', 'guru')
            ->where('status', 'aktif')
            ->get();

        $idGuruSudahInput = NilaiGuru::where('tahun_ajaran', $periodeAktif->tahun_ajaran)
            ->where('semester', $periodeAktif->semester)
            ->pluck('guru_id');

        $guruBelumInputNilai = $guruAktif->whereNotIn('id', $idGuruSudahInput)->count();

        return view('admin.dashboard', [
            'totalGuru' => User::where('role', 'guru')->count(),
            'guruTidakAktif' => User::where('role', 'guru')->where('status', 'tidak_aktif')->count(),
            'totalYayasan' => User::where('role', 'yayasan')->count(),
            'totalAdmin' => User::where('role', 'admin')->count(),
            'adminTidakAktif' => User::where('role', 'admin')->where('status', 'tidak_aktif')->count(),

            'guruBelumInputNilai' => $guruBelumInputNilai,
            'periodeAktif' => $periodeAktif,
        ]);
    }
}