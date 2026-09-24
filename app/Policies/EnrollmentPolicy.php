<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can list enrollments.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the enrollment.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return (bool) $user->instructor?->teaches($enrollment->course);
        }

        return $enrollment->student->user_id === $user->id;
    }

    /**
     * Determine whether the user can enroll students.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the enrollment.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can remove the enrollment.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }
}
