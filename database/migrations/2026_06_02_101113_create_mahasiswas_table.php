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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->string('name');
            $table->foreignId('study_program_id')->constrained('study_programs')->cascadeOnDelete();
            $table->foreignId('academic_advisor_id')->constrained('lecturers')->cascadeOnDelete();
            $table->year('enrollment_year');
            $table->enum('status', ['AKTIF', 'CUTI', 'LULUS', 'DO']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
