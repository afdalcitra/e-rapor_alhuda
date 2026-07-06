<?php

namespace App\Http\Controllers;

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
        $tahunAjaranList = NilaiGuru::select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $filterTahun = $request->input('tahun_ajaran');
        $filterSemester = $request->input('semester');

        $query = NilaiGuru::with(['guru', 'verifikator'])
            ->whereIn('status_verifikasi', ['menunggu', 'disetujui', 'ditolak'])
            ->when($filterTahun, fn($q) => $q->where('tahun_ajaran', $filterTahun))
            ->when($filterSemester, fn($q) => $q->where('semester', $filterSemester));

        // Summary untuk metric card
        $summary = (clone $query)
            ->select('status_verifikasi', DB::raw('count(*) as total'))
            ->groupBy('status_verifikasi')
            ->pluck('total', 'status_verifikasi');

        $nilaiGurus = (clone $query)
            ->where('status_verifikasi', 'menunggu')
            ->latest('dikirim_pada')
            ->paginate(20)
            ->withQueryString();

        return view('yayasan.index', compact(
            'nilaiGurus',
            'tahunAjaranList',
            'filterTahun',
            'filterSemester',
            'summary',
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