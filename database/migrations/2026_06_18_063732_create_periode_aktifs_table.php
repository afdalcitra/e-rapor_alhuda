<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periode_aktifs', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->timestamps();
        });

        // Seed satu baris default agar tabel tidak kosong saat pertama kali dipakai
        DB::table('periode_aktifs')->insert([
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
            'semester' => 'ganjil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_aktifs');
    }
};