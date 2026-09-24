<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(Gender::cases());

        return [
            'user_id' => User::factory()->student(),
            'department_id' => Department::factory(),
            'student_number' => 'STU-'.fake()->unique()->numerify('######'),
            'first_name' => fake()->firstName($gender === Gender::Male ? 'male' : 'female'),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-30 years', '-18 years'),
            'gender' => $gender,
            'phone' => fake()->numerify('+1-###-###-####'),
            'address' => fake()->address(),
            'photo' => null,
            'enrollment_date' => fake()->dateTimeBetween('-4 years', 'now'),
            'status' => StudentStatus::Active,
        ];
    }

    /**
     * Indicate that the student is no longer attending.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StudentStatus::Inactive,
        ]);
    }

    /**
     * Indicate that the student finished their programme.
     */
    public function graduated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StudentStatus::Graduated,
        ]);
    }

    /**
     * Indicate that the student is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StudentStatus::Suspended,
        ]);
    }
}
