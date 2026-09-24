<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::create(fake()->unique()->numberBetween(2015, 2035), 9, 1);

        return [
            'name' => $start->year.'/'.($start->year + 1),
            'start_date' => $start,
            'end_date' => $start->copy()->addYear()->subDay(),
            'is_current' => false,
        ];
    }

    /**
     * Indicate that the academic year is the one in progress.
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
        ]);
    }
}
