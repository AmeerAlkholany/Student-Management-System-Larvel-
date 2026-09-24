<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * The catalogue of seeded courses.
     *
     * @var array<int, array{code: string, name: string, department: string, credit_hours: int}>
     */
    private const array COURSES = [
        ['code' => 'CS-101', 'name' => 'Introduction to Programming', 'department' => 'Computer Science', 'credit_hours' => 3],
        ['code' => 'CS-201', 'name' => 'Data Structures', 'department' => 'Computer Science', 'credit_hours' => 4],
        ['code' => 'CS-310', 'name' => 'Database Systems', 'department' => 'Computer Science', 'credit_hours' => 3],
        ['code' => 'CS-340', 'name' => 'Web Application Development', 'department' => 'Computer Science', 'credit_hours' => 3],
        ['code' => 'CE-210', 'name' => 'Digital Logic Design', 'department' => 'Computer Engineering', 'credit_hours' => 4],
        ['code' => 'CE-320', 'name' => 'Microprocessor Systems', 'department' => 'Computer Engineering', 'credit_hours' => 3],
        ['code' => 'EE-150', 'name' => 'Circuit Analysis', 'department' => 'Electrical Engineering', 'credit_hours' => 4],
        ['code' => 'CV-120', 'name' => 'Engineering Mechanics', 'department' => 'Civil Engineering', 'credit_hours' => 3],
        ['code' => 'MA-101', 'name' => 'Calculus I', 'department' => 'Mathematics', 'credit_hours' => 4],
        ['code' => 'MA-220', 'name' => 'Linear Algebra', 'department' => 'Mathematics', 'credit_hours' => 3],
        ['code' => 'PH-110', 'name' => 'General Physics', 'department' => 'Physics', 'credit_hours' => 4],
        ['code' => 'AC-101', 'name' => 'Principles of Accounting', 'department' => 'Accounting', 'credit_hours' => 3],
        ['code' => 'BA-230', 'name' => 'Organisational Behaviour', 'department' => 'Business Administration', 'credit_hours' => 3],
    ];

    /**
     * Seed the course catalogue and assign the departmental instructor where one exists.
     */
    public function run(): void
    {
        $departmentIds = Department::query()->pluck('id', 'name');
        $instructorIds = Instructor::query()->pluck('id', 'department_id');

        foreach (self::COURSES as $course) {
            $departmentId = $departmentIds[$course['department']];

            Course::query()->create([
                'department_id' => $departmentId,
                'instructor_id' => $instructorIds[$departmentId] ?? null,
                'code' => $course['code'],
                'name' => $course['name'],
                'description' => "{$course['name']} offered by the {$course['department']} department.",
                'credit_hours' => $course['credit_hours'],
            ]);
        }
    }
}
