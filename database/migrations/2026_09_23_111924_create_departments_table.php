<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('faculty_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['faculty_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
