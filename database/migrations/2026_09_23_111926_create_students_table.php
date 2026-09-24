<?php

use App\Enums\StudentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('student_number', 50)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->date('enrollment_date');
            $table->string('status', 20)->default(StudentStatus::Active->value);
            $table->timestamps();

            $table->index('department_id');
            $table->index('status');
            $table->index(['last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
