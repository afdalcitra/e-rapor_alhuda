<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeAktif extends Model
{
    protected $table = 'periode_aktifs';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
    ];

    /**
     * Ambil periode aktif saat ini.
     * Selalu mengambil baris paling baru, untuk menghindari
     * kebingungan jika suatu saat ada lebih dari satu baris.
     */
    public static function aktif(): self
    {
        return static::latest('id')->first()
            ?? static::create([
                'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                'semester' => 'ganjil',
            ]);
    }

    /**
     * Set periode aktif baru.
     * Pakai updateOrCreate pada baris pertama agar tabel tetap 1 baris,
     * bukan terus bertambah setiap kali admin mengganti periode.
     */
    public static function setAktif(string $tahunAjaran, string $semester): self
    {
        $periode = static::aktif();
        $periode->update([
            'tahun_ajaran' => $tahunAjaran,
            'semester' => $semester,
        ]);

        return $periode;
    }
}