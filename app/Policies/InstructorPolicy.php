<?php

namespace App\Policies;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InstructorPolicy
{
    /**
     * Determine whether the user can list instructors.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the instructor.
     */
    public function view(User $user, Instructor $instructor): bool
    {
        return $user->isAdmin() || $instructor->user_id === $user->id;
    }

    /**
     * Determine whether the user can create instructors.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the instructor.
     */
    public function update(User $user, Instructor $instructor): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the instructor.
     *
     * Courses keep a nullable reference to their instructor, so the assignment
     * has to be cleared deliberately before the instructor is removed.
     */
    public function delete(User $user, Instructor $instructor): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $instructor->courses()->exists()
            ? Response::deny(__('This instructor is still assigned to courses and cannot be deleted.'))
            : Response::allow();
    }
}
