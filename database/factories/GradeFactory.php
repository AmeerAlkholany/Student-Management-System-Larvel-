<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Services\GradeCalculationService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $assignment = fake()->randomFloat(2, 0, Grade::MAX_ASSIGNMENT);
        $midterm = fake()->randomFloat(2, 0, Grade::MAX_MIDTERM);
        $final = fake()->randomFloat(2, 0, Grade::MAX_FINAL);

        $scores = app(GradeCalculationService::class)->calculate($assignment, $midterm, $final);

        return [
            'enrollment_id' => Enrollment::factory(),
            ...$scores,
        ];
    }
}
