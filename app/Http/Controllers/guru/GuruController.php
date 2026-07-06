<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\NilaiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruController extends Controller
{
    // ── Dashboard Guru ────────────────────────────────────

    public function index(Request $request)
    {
        $user = Auth::user();

        $tahunAjaranList = NilaiGuru::where('guru_id', $user->id)
            ->select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $filterTahun = $request->input('tahun_ajaran');
        $filterSemester = $request->input('semester');

        // Semua riwayat milik guru yang login
        $riwayat = NilaiGuru::with(['verifikator'])
            ->where('guru_id', $user->id)
            ->when($filterTahun, fn($q) => $q->where('tahun_ajaran', $filterTahun))
            ->when($filterSemester, fn($q) => $q->where('semester', $filterSemester))
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('semester')
            ->get();

        // Metric card — rata-rata hanya dari yang disetujui
        $disetujui = $riwayat->where('status_verifikasi', 'disetujui');

        $metrics = [
            'rata_rata' => $disetujui->count()
                ? round($disetujui->avg('total_nilai'), 1)
                : null,
            'disetujui' => $disetujui->count(),
            'menunggu' => $riwayat->where('status_verifikasi', 'menunggu')->count(),
            'ditolak' => $riwayat->where('status_verifikasi', 'ditolak')->count(),
        ];

        // Data chart per semester (hanya yang disetujui, urut kronologis)
        $chartSemester = NilaiGuru::where('guru_id', $user->id)
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('tahun_ajaran')
            ->orderByRaw("FIELD(semester, 'ganjil', 'genap')")
            ->get()
            ->map(fn($n) => [
                'label' => $n->tahun_ajaran . ' ' . ucfirst($n->semester),
                'value' => (float) $n->total_nilai,
            ]);

        // Data chart per tahun ajaran (rata-rata dari semua semester)
        $chartTahun = NilaiGuru::where('guru_id', $user->id)
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('tahun_ajaran')
            ->groupBy('tahun_ajaran')
            ->selectRaw('tahun_ajaran as label, ROUND(AVG(total_nilai), 1) as value')
            ->get();

        return view('guru.index', compact(
            'riwayat',
            'tahunAjaranList',
            'filterTahun',
            'filterSemester',
            'metrics',
            'chartSemester',
            'chartTahun',
        ));
    }

    // ── Download PDF ──────────────────────────────────────

    public function downloadPdf(NilaiGuru $nilaiGuru)
    {
        abort_if($nilaiGuru->guru_id !== Auth::id(), 403);
        abort_if($nilaiGuru->status_verifikasi !== 'disetujui', 403, 'Rapor belum diverifikasi.');

        $nilaiGuru->load(['guru', 'verifikator']);

        $pdf = Pdf::loadView('guru.pdf', compact('nilaiGuru'))
            ->setPaper('a4', 'portrait');

        $filename = sprintf(
            'rapor_%s_%s_%s.pdf',
            str($nilaiGuru->guru->nama_lengkap)->slug(),
            str($nilaiGuru->tahun_ajaran)->slug(),
            $nilaiGuru->semester
        );

        return $pdf->download($filename);
    }
}