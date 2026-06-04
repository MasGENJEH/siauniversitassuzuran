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
        Schema::create('kelas_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelas')->unique();
            $table->foreignId('id_mk')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->foreignId('id_ta')->constrained('tahun_akademiks')->cascadeOnDelete();
            $table->string('nama_kelas');
            $table->string('hari'); // Input KAPITAL, cth: "SENIN"
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_kuliahs');
    }
};
