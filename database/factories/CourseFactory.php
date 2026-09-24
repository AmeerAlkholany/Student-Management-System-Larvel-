<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'instructor_id' => Instructor::factory(),
            'code' => mb_strtoupper(fake()->unique()->bothify('???-###')),
            'name' => ucwords(fake()->words(3, true)),
            'description' => fake()->paragraph(),
            'credit_hours' => fake()->numberBetween(1, 6),
        ];
    }

    /**
     * Indicate that no instructor teaches the course yet.
     */
    public function unassigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'instructor_id' => null,
        ]);
    }
}
