<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can list courses.
     *
     * Every role has a course listing; the query itself is scoped per role.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the course.
     */
    public function view(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return (bool) $user->instructor?->teaches($course);
        }

        return (bool) $user->student?->isEnrolledIn($course);
    }

    /**
     * Determine whether the user can create courses.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the course.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the course.
     */
    public function delete(User $user, Course $course): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $course->enrollments()->exists()
            ? Response::deny(__('This course still has enrollments and cannot be deleted.'))
            : Response::allow();
    }

    /**
     * Determine whether the user can record attendance and grades for the course.
     */
    public function manageAcademics(User $user, Course $course): bool
    {
        return $user->isAdmin() || (bool) $user->instructor?->teaches($course);
    }
}
