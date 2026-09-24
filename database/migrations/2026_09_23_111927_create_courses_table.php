<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('instructor_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('credit_hours')->default(3);
            $table->timestamps();

            $table->index('department_id');
            $table->index('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
