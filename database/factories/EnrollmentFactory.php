<?php

namespace Database\Factories;

use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'semester_id' => Semester::factory(),
            'enrolled_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => EnrollmentStatus::Enrolled,
        ];
    }

    /**
     * Indicate that the student finished the course.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EnrollmentStatus::Completed,
        ]);
    }

    /**
     * Indicate that the student left the course.
     */
    public function dropped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EnrollmentStatus::Dropped,
        ]);
    }
}
