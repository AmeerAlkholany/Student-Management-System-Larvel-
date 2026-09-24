<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->instructor(),
            'department_id' => Department::factory(),
            'employee_number' => 'EMP-'.fake()->unique()->numerify('#####'),
            'phone' => fake()->numerify('+1-###-###-####'),
            'specialization' => fake()->words(2, true),
        ];
    }
}
