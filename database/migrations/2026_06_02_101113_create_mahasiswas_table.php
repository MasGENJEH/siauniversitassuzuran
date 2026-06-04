<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->string('nama'); // Input KAPITAL
            $table->foreignId('id_prodi')->constrained('prodis')->cascadeOnDelete();
            $table->foreignId('id_dosen_pa')->constrained('dosens')->cascadeOnDelete();
            $table->year('tahun_masuk');
            $table->enum('status_mahasiswa', ['AKTIF', 'CUTI', 'LULUS', 'DO']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
