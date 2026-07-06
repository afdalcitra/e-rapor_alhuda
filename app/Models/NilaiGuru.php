<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'guru_id',
    'tahun_ajaran',
    'semester',
    'nilai_tahsin',
    'nilai_upp',
    'nilai_ortu',
    'nilai_teman',
    'nilai_disiplin',
    'nilai_absen',
    'nilai_ajar',
    'nilai_supervisi',
    'total_nilai',
    'predikat',
    'status_verifikasi',
    'diverifikasi_oleh',
    'dikirim_pada',
    'diverifikasi_pada',
    'catatan_admin',
    'catatan_yayasan',
])]
class NilaiGuru extends Model
{
    protected function casts(): array
    {
        return [
            'diverifikasi_pada' => 'datetime',
            'dikirim_pada' => 'datetime',
            'total_nilai' => 'decimal:2',
        ];
    }

    // ── Relasi ────────────────────────────────────────────

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    // ── Scopes ────────────────────────────────────────────

    public function scopeMenunggu($query)
    {
        return $query->where('status_verifikasi', 'menunggu');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', 'ditolak');
    }

    // ── Helpers ───────────────────────────────────────────

    public function hitungTotalNilai(): float
    {
        return round(collect([
            $this->nilai_tahsin,
            $this->nilai_upp,
            $this->nilai_ortu,
            $this->nilai_teman,
            $this->nilai_disiplin,
            $this->nilai_absen,
            $this->nilai_ajar,
            $this->nilai_supervisi,
        ])->avg(), 2);
    }

    public function hitungPredikat(): string
    {
        $total = $this->hitungTotalNilai();

        return match (true) {
            $total >= 90 => 'A',
            $total >= 80 => 'B',
            $total >= 70 => 'C',
            default => 'D',
        };
    }

    /**
     * Hitung dan simpan total_nilai + predikat sekaligus.
     * Panggil sebelum save() ketika admin menginput nilai.
     */
    public function kalkulasi(): static
    {
        $this->total_nilai = $this->hitungTotalNilai();
        $this->predikat = $this->hitungPredikat();

        return $this;
    }
}