<?php

namespace App\Policies;

use App\Models\Semester;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SemesterPolicy
{
    /**
     * Determine whether the user can list semesters.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the semester.
     */
    public function view(User $user, Semester $semester): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create semesters.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the semester.
     */
    public function update(User $user, Semester $semester): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the semester.
     */
    public function delete(User $user, Semester $semester): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $semester->enrollments()->exists()
            ? Response::deny(__('This semester still has enrollments and cannot be deleted.'))
            : Response::allow();
    }
}
