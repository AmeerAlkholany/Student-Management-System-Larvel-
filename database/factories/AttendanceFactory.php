<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
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
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => fake()->randomElement(AttendanceStatus::cases()),
            'notes' => null,
        ];
    }

    /**
     * Indicate that the student attended the class.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttendanceStatus::Present,
        ]);
    }

    /**
     * Indicate that the student missed the class.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttendanceStatus::Absent,
        ]);
    }
}
