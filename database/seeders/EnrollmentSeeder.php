<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\Student;
use App\Services\GradeCalculationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class EnrollmentSeeder extends Seeder
{
    /**
     * How many courses each enrollable student takes.
     */
    private const int COURSES_PER_STUDENT = 3;

    /**
     * How many class sessions are recorded per enrollment.
     */
    private const int SESSIONS_PER_COURSE = 10;

    public function __construct(private readonly GradeCalculationService $gradeCalculation) {}

    /**
     * Seed enrollments for the current semester along with grades and attendance.
     */
    public function run(): void
    {
        $semester = Semester::query()->current()->firstOrFail();
        $courses = Course::query()->get();

        /** @var Collection<int, Student> $students */
        $students = Student::query()->get()->filter(fn (Student $student): bool => $student->isEnrollable());

        foreach ($students as $student) {
            $this->enroll($student, $this->coursesFor($student, $courses), $semester);
        }
    }

    /**
     * Pick the courses a student takes, preferring their own department.
     *
     * @param  Collection<int, Course>  $courses
     * @return Collection<int, Course>
     */
    private function coursesFor(Student $student, Collection $courses): Collection
    {
        $own = $courses->where('department_id', $student->department_id)->take(self::COURSES_PER_STUDENT);

        return $own->concat(
            $courses->whereNotIn('id', $own->pluck('id'))->take(self::COURSES_PER_STUDENT - $own->count())
        )->values();
    }

    /**
     * Enroll a student into the given courses and record their grades and attendance.
     *
     * @param  Collection<int, Course>  $courses
     */
    private function enroll(Student $student, Collection $courses, Semester $semester): void
    {
        foreach ($courses as $index => $course) {
            $enrollment = Enrollment::query()->create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'semester_id' => $semester->id,
                'enrolled_at' => $semester->start_date,
                'status' => $index === 0 ? EnrollmentStatus::Completed : EnrollmentStatus::Enrolled,
            ]);

            if ($enrollment->status === EnrollmentStatus::Completed) {
                $this->recordGrade($enrollment);
            }

            $this->recordAttendance($student, $course, $semester);
        }
    }

    /**
     * Store a realistic grade for a completed enrollment.
     */
    private function recordGrade(Enrollment $enrollment): void
    {
        Grade::query()->create([
            'enrollment_id' => $enrollment->id,
            ...$this->gradeCalculation->calculate(
                fake()->randomFloat(1, 4, Grade::MAX_ASSIGNMENT),
                fake()->randomFloat(1, 12, Grade::MAX_MIDTERM),
                fake()->randomFloat(1, 25, Grade::MAX_FINAL),
            ),
        ]);
    }

    /**
     * Store one weekly attendance record per class session.
     */
    private function recordAttendance(Student $student, Course $course, Semester $semester): void
    {
        $date = Carbon::parse($semester->start_date);
        $rows = [];

        for ($session = 0; $session < self::SESSIONS_PER_COURSE; $session++) {
            $rows[] = [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'date' => $date->copy()->addWeeks($session)->toDateString(),
                'status' => fake()->randomElement([
                    AttendanceStatus::Present,
                    AttendanceStatus::Present,
                    AttendanceStatus::Present,
                    AttendanceStatus::Late,
                    AttendanceStatus::Absent,
                    AttendanceStatus::Excused,
                ])->value,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Attendance::query()->insert($rows);
    }
}
