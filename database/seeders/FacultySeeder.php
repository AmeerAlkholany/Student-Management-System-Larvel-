<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * The faculties and the departments that belong to each of them.
     *
     * @var array<string, array<int, string>>
     */
    private const array STRUCTURE = [
        'Faculty of Engineering' => [
            'Computer Engineering',
            'Electrical Engineering',
            'Civil Engineering',
        ],
        'Faculty of Science' => [
            'Computer Science',
            'Mathematics',
            'Physics',
        ],
        'Faculty of Business' => [
            'Accounting',
            'Business Administration',
        ],
    ];

    /**
     * Seed the faculties together with their departments.
     */
    public function run(): void
    {
        foreach (self::STRUCTURE as $facultyName => $departments) {
            $faculty = Faculty::query()->create([
                'name' => $facultyName,
                'description' => "Academic programmes offered by the {$facultyName}.",
            ]);

            foreach ($departments as $departmentName) {
                Department::query()->create([
                    'faculty_id' => $faculty->id,
                    'name' => $departmentName,
                    'description' => "The {$departmentName} department.",
                ]);
            }
        }
    }
}
