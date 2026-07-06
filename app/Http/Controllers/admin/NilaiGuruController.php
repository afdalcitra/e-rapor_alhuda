<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NilaiGuru;
use App\Models\User;
use App\Models\PeriodeAktif;

class NilaiGuruController extends Controller
{
    public function index(Request $request)
    {
        $periodeAktif = PeriodeAktif::aktif();

        // Filter di halaman ini boleh berbeda dari periode aktif (untuk lihat data lama),
        // tapi defaultnya selalu mengikuti periode aktif.
        $tahunAjaran = $request->get('tahun_ajaran', $periodeAktif->tahun_ajaran);
        $semester = $request->get('semester', $periodeAktif->semester);

        $daftarTahunAjaran = NilaiGuru::select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        // pastikan tahun ajaran aktif selalu ada di pilihan, walau belum ada datanya
        if (!$daftarTahunAjaran->contains($tahunAjaran)) {
            $daftarTahunAjaran->prepend($tahunAjaran);
        }

        $guruAktif = User::where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        // guru yang SUDAH punya nilai pada periode ini
        $guruSudahInput = NilaiGuru::with('guru')
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->get();

        $idGuruSudahInput = $guruSudahInput->pluck('guru_id');

        // guru aktif yang BELUM punya nilai pada periode ini
        $guruBelumInput = $guruAktif->whereNotIn('id', $idGuruSudahInput)->values();

        return view('admin.nilai-guru.index', [
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
            'daftarTahunAjaran' => $daftarTahunAjaran,
            'totalGuruAktif' => $guruAktif->count(),
            'guruSudahInput' => $guruSudahInput,
            'guruBelumInput' => $guruBelumInput,
            'periodeAktif' => $periodeAktif,
        ]);
    }

    /**
     * Set periode (tahun ajaran + semester) yang sedang ditampilkan
     * menjadi periode aktif sistem. Dipanggil dari tombol
     * "Jadikan periode aktif" di halaman index.
     */
    public function setPeriodeAktif(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:ganjil,genap',
        ]);

        PeriodeAktif::setAktif($validated['tahun_ajaran'], $validated['semester']);

        return redirect()
            ->route('admin.nilai-guru.index', [
                'tahun_ajaran' => $validated['tahun_ajaran'],
                'semester' => $validated['semester'],
            ])
            ->with('success', 'Periode aktif berhasil diperbarui.');
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view(
            'admin.nilai-guru.create',
            compact('gurus')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:users,id',

            'tahun_ajaran' => 'required',
            'semester' => 'required',

            'nilai_tahsin' => 'required|numeric|min:0|max:100',
            'nilai_upp' => 'required|numeric|min:0|max:100',
            'nilai_ortu' => 'required|numeric|min:0|max:100',
            'nilai_teman' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_absen' => 'required|numeric|min:0|max:100',
            'nilai_ajar' => 'required|numeric|min:0|max:100',
            'nilai_supervisi' => 'required|numeric|min:0|max:100',

            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $validated['status_verifikasi'] = 'menunggu';

        $nilaiGuru = new NilaiGuru($validated);

        $nilaiGuru->total_nilai =
            $nilaiGuru->hitungTotalNilai();

        $nilaiGuru->predikat =
            $nilaiGuru->hitungPredikat();

        $nilaiGuru->save();

        return redirect()
            ->route('admin.nilai-guru.index')
            ->with('success', 'Nilai berhasil ditambahkan');
    }

    public function edit(NilaiGuru $nilaiGuru)
    {
        $gurus = User::where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view(
            'admin.nilai-guru.edit',
            compact(
                'nilaiGuru',
                'gurus'
            )
        );
    }

    public function update(
        Request $request,
        NilaiGuru $nilaiGuru
    ) {
        $validated = $request->validate([
            'guru_id' => 'required|exists:users,id',

            'tahun_ajaran' => 'required',
            'semester' => 'required',

            'nilai_tahsin' => 'required|numeric|min:0|max:100',
            'nilai_upp' => 'required|numeric|min:0|max:100',
            'nilai_ortu' => 'required|numeric|min:0|max:100',
            'nilai_teman' => 'required|numeric|min:0|max:100',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_absen' => 'required|numeric|min:0|max:100',
            'nilai_ajar' => 'required|numeric|min:0|max:100',
            'nilai_supervisi' => 'required|numeric|min:0|max:100',

            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $validated['status_verifikasi'] = 'menunggu';

        // isi model dengan data baru
        $nilaiGuru->fill($validated);

        // hitung ulang total
        $nilaiGuru->total_nilai =
            $nilaiGuru->hitungTotalNilai();

        // hitung ulang predikat
        $nilaiGuru->predikat =
            $nilaiGuru->hitungPredikat();

        // simpan
        $nilaiGuru->save();

        return redirect()
            ->route('admin.nilai-guru.index')
            ->with(
                'success',
                'Nilai berhasil diperbarui'
            );
    }
}