<?php

namespace App\Http\Controllers\yayasan;

use App\Http\Controllers\Controller;
use App\Models\NilaiGuru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class YayasanController extends Controller
{
    // ── Dashboard Verifikasi ──────────────────────────────

    public function index(Request $request)
{
    $filterTahun    = $request->get('tahun_ajaran');
    $filterSemester = $request->get('semester');
 
    $tahunAjaranList = NilaiGuru::select('tahun_ajaran')
        ->distinct()
        ->orderByDesc('tahun_ajaran')
        ->pluck('tahun_ajaran');
 
    // Query dasar: filter tahun & semester kalau dipilih
    $base = NilaiGuru::with('guru')
        ->when($filterTahun,    fn($q) => $q->where('tahun_ajaran', $filterTahun))
        ->when($filterSemester, fn($q) => $q->where('semester', $filterSemester))
        ->latest();
 
    // Masing-masing status dipaginasi terpisah dengan append filter
    $menunggu  = (clone $base)->where('status_verifikasi', 'menunggu')
        ->paginate(10, ['*'], 'page_menunggu')
        ->appends($request->only(['tahun_ajaran', 'semester']));
 
    $disetujui = (clone $base)->where('status_verifikasi', 'disetujui')
        ->paginate(10, ['*'], 'page_disetujui')
        ->appends($request->only(['tahun_ajaran', 'semester']));
 
    $ditolak   = (clone $base)->where('status_verifikasi', 'ditolak')
        ->paginate(10, ['*'], 'page_ditolak')
        ->appends($request->only(['tahun_ajaran', 'semester']));
 
    return view('yayasan.index', compact(
        'menunggu',
        'disetujui',
        'ditolak',
        'filterTahun',
        'filterSemester',
        'tahunAjaranList',
    ));
}
 

    // ── Verifikasi satu entri ─────────────────────────────

    public function verify(NilaiGuru $nilaiGuru)
    {
        abort_if($nilaiGuru->status_verifikasi !== 'menunggu', 422);

        $nilaiGuru->update([
            'status_verifikasi' => 'disetujui',
            'diverifikasi_oleh' => Auth::id(),
            'diverifikasi_pada' => now(),
            'catatan_yayasan' => null,
        ]);

        return back()->with('success', "Nilai {$nilaiGuru->guru->nama_lengkap} berhasil diverifikasi.");
    }

    // ── Tolak satu entri ──────────────────────────────────

    public function reject(Request $request, NilaiGuru $nilaiGuru)
    {
        abort_if($nilaiGuru->status_verifikasi !== 'menunggu', 422);

        $request->validate([
            'catatan_yayasan' => 'nullable|string|max:500',
        ]);

        $nilaiGuru->update([
            'status_verifikasi' => 'ditolak',
            'diverifikasi_oleh' => Auth::id(),
            'diverifikasi_pada' => now(),
            'catatan_yayasan' => $request->catatan_yayasan,
        ]);

        return back()->with('success', "Nilai {$nilaiGuru->guru->nama_lengkap} telah ditolak.");
    }

    // ── Verif semua pending (sesuai filter) ───────────────

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'nullable|string',
            'semester' => 'nullable|in:ganjil,genap',
        ]);

        $updated = NilaiGuru::where('status_verifikasi', 'menunggu')
            ->when($request->tahun_ajaran, fn($q) => $q->where('tahun_ajaran', $request->tahun_ajaran))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->update([
                'status_verifikasi' => 'disetujui',
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
            ]);

        return back()->with('success', "{$updated} penilaian berhasil diverifikasi.");
    }

    // ── Tolak semua pending (sesuai filter) ───────────────

    public function bulkReject(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'nullable|string',
            'semester' => 'nullable|in:ganjil,genap',
            'catatan_yayasan' => 'nullable|string|max:500',
        ]);

        $updated = NilaiGuru::where('status_verifikasi', 'menunggu')
            ->when($request->tahun_ajaran, fn($q) => $q->where('tahun_ajaran', $request->tahun_ajaran))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->update([
                'status_verifikasi' => 'ditolak',
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
                'catatan_yayasan' => $request->catatan_yayasan,
            ]);

        return back()->with('success', "{$updated} penilaian telah ditolak.");
    }
}