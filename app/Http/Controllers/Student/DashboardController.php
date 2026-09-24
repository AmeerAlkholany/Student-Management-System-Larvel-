<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the student their enrollments, latest grades and attendance rate.
     */
    public function __invoke(Request $request): Response
    {
        $student = $request->user()->student;

        abort_if($student === null, 403, __('Your account is not linked to a student profile.'));

        $student->load('department:id,name');

        $currentSemester = Semester::query()->current()->first(['id', 'name']);

        return Inertia::render('student/dashboard', [
            'student' => [
                'id' => $student->id,
                'studentNumber' => $student->student_number,
                'fullName' => $student->full_name,
                'department' => $student->department->name,
                'status' => $student->status,
                'attendancePercentage' => $student->attendancePercentage(),
            ],
            'currentSemester' => $currentSemester,
            'enrollments' => $student->enrollments()
                ->when($currentSemester, fn ($query) => $query->where('semester_id', $currentSemester->id))
                ->with(['course:id,code,name,credit_hours', 'grade:id,enrollment_id,total,letter_grade'])
                ->get(['id', 'course_id', 'semester_id', 'status']),
            'latestGrades' => $student->grades()
                ->with('enrollment.course:id,code,name')
                ->latest('grades.updated_at')
                ->take(5)
                ->get(['grades.id', 'grades.enrollment_id', 'grades.total', 'grades.letter_grade', 'grades.updated_at']),
        ]);
    }
}
