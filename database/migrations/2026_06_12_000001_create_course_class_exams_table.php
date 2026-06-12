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
        Schema::create('course_class_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_class_id')->constrained('course_classes')->onDelete('cascade');
            $table->enum('exam_type', ['UTS', 'UAS']);
            $table->date('tanggal');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->onDelete('set null'); // Pengawas
            $table->enum('method', ['online', 'offline'])->default('offline');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique constraint to prevent multiple UTS or UAS for the same class
            $table->unique(['course_class_id', 'exam_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_class_exams');
    }
};
