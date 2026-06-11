<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_gurus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('tahun_ajaran');
            $table->enum('semester', [
                'ganjil',
                'genap'
            ]);

            $table->unsignedTinyInteger('nilai_tahsin');
            $table->unsignedTinyInteger('nilai_upp');
            $table->unsignedTinyInteger('nilai_ortu');
            $table->unsignedTinyInteger('nilai_teman');
            $table->unsignedTinyInteger('nilai_disiplin');
            $table->unsignedTinyInteger('nilai_absen');
            $table->unsignedTinyInteger('nilai_ajar');
            $table->unsignedTinyInteger('nilai_supervisi');

            $table->decimal('total_nilai', 5, 2)->nullable();
            $table->string('predikat')->nullable();

            $table->enum('status_verifikasi', [
                'draft',
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('draft');

            $table->foreignId('diverifikasi_oleh')
                ->nullable()
                ->constrained('users');

            $table->timestamp('diverifikasi_pada')->nullable();

            $table->text('catatan_yayasan')->nullable();

            $table->timestamps();

            $table->unique([
                'guru_id',
                'tahun_ajaran',
                'semester'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_gurus');
    }
};
