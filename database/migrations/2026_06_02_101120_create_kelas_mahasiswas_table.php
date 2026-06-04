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
        Schema::create('kelas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('id_kelas')->constrained('kelas_kuliahs')->cascadeOnDelete();

            // Kolom opsional tambahan untuk manajemen KHS
            $table->float('nilai_akhir')->nullable();
            $table->char('nilai_huruf', 1)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mahasiswas');
    }
};
