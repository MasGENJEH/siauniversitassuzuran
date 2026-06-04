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
        Schema::create('dosen_pengampus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')->constrained('kelas_kuliahs')->cascadeOnDelete();
            $table->foreignId('id_dosen')->constrained('dosens')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pengampus');
    }
};
