<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StudentStatus;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Faculty;
use App\Models\Instructor;
use App\Models\Semester;
use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the institution-wide statistics and the latest enrollments.
     */
    public function __invoke(): Response
    {
        return Inertia::render('admin/dashboard', [
            'stats' => [
                'students' => Student::query()->count(),
                'activeStudents' => Student::query()->where('status', StudentStatus::Active)->count(),
                'instructors' => Instructor::query()->count(),
                'faculties' => Faculty::query()->count(),
                'departments' => Department::query()->count(),
                'courses' => Course::query()->count(),
                'enrollments' => Enrollment::query()->count(),
            ],
            'currentSemester' => Semester::query()
                ->current()
                ->with('academicYear:id,name')
                ->first(['id', 'academic_year_id', 'name', 'start_date', 'end_date']),
            'recentEnrollments' => Enrollment::query()
                ->with([
                    'student:id,student_number,first_name,last_name',
                    'course:id,code,name',
                ])
                ->latest('enrolled_at')
                ->take(8)
                ->get(['id', 'student_id', 'course_id', 'enrolled_at', 'status']),
        ]);
    }
}
