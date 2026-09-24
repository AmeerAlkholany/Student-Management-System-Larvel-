<?php

namespace App\Http\Controllers\Instructor;

use App\Enums\EnrollmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the courses assigned to the instructor and their headline numbers.
     */
    public function __invoke(Request $request): Response
    {
        $instructor = $request->user()->instructor;

        abort_if($instructor === null, 403, __('Your account is not linked to an instructor profile.'));

        $courseIds = $instructor->courses()->pluck('id');

        return Inertia::render('instructor/dashboard', [
            'stats' => [
                'courses' => $courseIds->count(),
                'students' => Enrollment::query()
                    ->whereIn('course_id', $courseIds)
                    ->distinct()
                    ->count('student_id'),
                'activeEnrollments' => Enrollment::query()
                    ->whereIn('course_id', $courseIds)
                    ->where('status', EnrollmentStatus::Enrolled)
                    ->count(),
            ],
            'courses' => Course::query()
                ->whereKey($courseIds)
                ->with('department:id,name')
                ->withCount('enrollments')
                ->orderBy('code')
                ->get(['id', 'department_id', 'code', 'name', 'credit_hours']),
            'recentEnrollments' => Enrollment::query()
                ->whereIn('course_id', $courseIds)
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
