<?php

namespace Database\Seeders;

use App\Enums\StudentStatus;
use App\Enums\UserRole;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * The password shared by every seeded development account.
     */
    public const string DEVELOPMENT_PASSWORD = 'password';

    /**
     * The instructors to seed, keyed by the department they belong to.
     *
     * @var array<int, array{name: string, email: string, department: string, specialization: string}>
     */
    private const array INSTRUCTORS = [
        [
            'name' => 'Layla Haddad',
            'email' => 'layla.haddad@example.com',
            'department' => 'Computer Science',
            'specialization' => 'Software Engineering',
        ],
        [
            'name' => 'Omar Saleh',
            'email' => 'omar.saleh@example.com',
            'department' => 'Computer Engineering',
            'specialization' => 'Embedded Systems',
        ],
        [
            'name' => 'Nadia Farouk',
            'email' => 'nadia.farouk@example.com',
            'department' => 'Business Administration',
            'specialization' => 'Organisational Behaviour',
        ],
    ];

    /**
     * Seed the administrator, the instructors and the student body.
     */
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedInstructors();
        $this->seedStudents();
    }

    /**
     * Create the default administrator used during local development.
     */
    private function seedAdmin(): void
    {
        User::query()->create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => self::DEVELOPMENT_PASSWORD,
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Create an instructor profile for each seeded instructor account.
     */
    private function seedInstructors(): void
    {
        foreach (self::INSTRUCTORS as $index => $instructor) {
            $user = User::query()->create([
                'name' => $instructor['name'],
                'email' => $instructor['email'],
                'password' => self::DEVELOPMENT_PASSWORD,
                'role' => UserRole::Instructor,
                'email_verified_at' => now(),
            ]);

            Instructor::query()->create([
                'user_id' => $user->id,
                'department_id' => Department::query()->where('name', $instructor['department'])->value('id'),
                'employee_number' => sprintf('EMP-%03d', $index + 1),
                'phone' => sprintf('+1-555-01%02d', $index + 1),
                'specialization' => $instructor['specialization'],
            ]);
        }
    }

    /**
     * Create fifteen students spread across the departments and statuses.
     */
    private function seedStudents(): void
    {
        $departmentIds = Department::query()->pluck('id')->all();
        $enrollmentYear = now()->year;

        Student::factory()
            ->count(15)
            ->state(new Sequence(fn (Sequence $sequence): array => [
                'department_id' => $departmentIds[$sequence->index % count($departmentIds)],
                'student_number' => sprintf('STU-%d%03d', $enrollmentYear, $sequence->index + 1),
                'status' => match ($sequence->index) {
                    12 => StudentStatus::Inactive,
                    13 => StudentStatus::Graduated,
                    14 => StudentStatus::Suspended,
                    default => StudentStatus::Active,
                },
            ]))
            ->create()
            ->each(function (Student $student): void {
                $student->user()->update([
                    'name' => $student->full_name,
                    'email' => $this->studentEmail($student),
                ]);
            });
    }

    /**
     * Build a predictable email address for a seeded student.
     */
    private function studentEmail(Student $student): string
    {
        $handle = Str::of($student->full_name)->lower()->replace(' ', '.')->ascii()->replaceMatches('/[^a-z.]/', '');

        return "{$handle}.{$student->id}@example.com";
    }
}
