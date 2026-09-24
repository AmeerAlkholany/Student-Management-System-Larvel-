<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('enrollment_id')
                ->unique()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->decimal('assignment', 5, 2)->default(0);
            $table->decimal('midterm', 5, 2)->default(0);
            $table->decimal('final', 5, 2)->default(0);
            $table->decimal('total', 5, 2)->default(0);
            $table->string('letter_grade', 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
