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
        Schema::create('course_class_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_class_id')->constrained('course_classes')->cascadeOnDelete();
            $table->date('tanggal');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique(['course_class_id', 'tanggal'], 'meeting_unique');
        });

        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_class_meeting_id')->constrained('course_class_meetings')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa'])->nullable();
            $table->timestamps();

            $table->unique(['course_class_meeting_id', 'student_id'], 'absensi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
        Schema::dropIfExists('course_class_meetings');
    }
};
