<?php

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('course_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('semester_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamp('enrolled_at');
            $table->string('status', 20)->default(EnrollmentStatus::Enrolled->value);
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'semester_id']);
            $table->index('course_id');
            $table->index('semester_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
