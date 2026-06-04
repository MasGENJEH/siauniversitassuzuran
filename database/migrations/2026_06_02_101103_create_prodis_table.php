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
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_fakultas')->constrained('fakultas')->cascadeOnDelete();
            $table->string('kode_prodi')->unique();
            $table->string('prefix_nim', 3)->nullable();
            $table->string('nama_prodi');
            $table->enum('jenjang', ['D3', 'S1', 'S2', 'S3']);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
