<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table): void {
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
            $table->string('employee_number', 50)->unique();
            $table->string('phone', 50)->nullable();
            $table->string('specialization')->nullable();
            $table->timestamps();

            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
