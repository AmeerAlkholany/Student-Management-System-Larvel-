<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    /**
     * Determine whether the user can list grades.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    /**
     * Determine whether the user can view the grade.
     */
    public function view(User $user, Grade $grade): bool
    {
        return $this->reaches($user, $grade->enrollment, readOnly: true);
    }

    /**
     * Determine whether the user can record a grade for the given enrollment.
     */
    public function create(User $user, ?Enrollment $enrollment = null): bool
    {
        if ($enrollment === null) {
            return $user->isAdmin() || $user->isInstructor();
        }

        return $this->reaches($user, $enrollment, readOnly: false);
    }

    /**
     * Determine whether the user can update the grade.
     */
    public function update(User $user, Grade $grade): bool
    {
        return $this->reaches($user, $grade->enrollment, readOnly: false);
    }

    /**
     * Determine whether the user can delete the grade.
     */
    public function delete(User $user, Grade $grade): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user reaches the enrollment behind a grade.
     *
     * Administrators reach every enrollment, instructors only those belonging
     * to a course they teach, and students only their own — and then only to
     * read.
     */
    private function reaches(User $user, Enrollment $enrollment, bool $readOnly): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return (bool) $user->instructor?->teaches($enrollment->course);
        }

        return $readOnly && $enrollment->student->user_id === $user->id;
    }
}
