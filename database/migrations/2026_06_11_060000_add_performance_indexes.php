<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add composite indexes for frequently queried columns to improve performance.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['student_id', 'course_class_id'], 'enrollments_student_class_index');
        });

        Schema::table('class_instructors', function (Blueprint $table) {
            $table->index(['course_class_id', 'lecturer_id'], 'class_instructors_class_lecturer_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->index(['study_program_id'], 'students_study_program_index');
            $table->index(['academic_advisor_id'], 'students_advisor_index');
            $table->index(['status'], 'students_status_index');
        });

        Schema::table('course_classes', function (Blueprint $table) {
            $table->index(['course_id', 'academic_year_id'], 'course_classes_course_year_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enrollments_student_class_index');
        });

        Schema::table('class_instructors', function (Blueprint $table) {
            $table->dropIndex('class_instructors_class_lecturer_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_study_program_index');
            $table->dropIndex('students_advisor_index');
            $table->dropIndex('students_status_index');
        });

        Schema::table('course_classes', function (Blueprint $table) {
            $table->dropIndex('course_classes_course_year_index');
        });
    }
};
