<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance(fake()->dateTimeBetween('-1 year', 'now'));

        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => fake()->randomElement(['First Semester', 'Second Semester', 'Summer']),
            'start_date' => $start,
            'end_date' => $start->copy()->addMonths(4),
            'is_current' => false,
        ];
    }

    /**
     * Indicate that the semester is the one in progress.
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
        ]);
    }
}
