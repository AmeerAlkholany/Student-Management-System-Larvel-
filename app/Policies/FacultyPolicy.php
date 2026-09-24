<?php

namespace App\Policies;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FacultyPolicy
{
    /**
     * Determine whether the user can list faculties.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the faculty.
     */
    public function view(User $user, Faculty $faculty): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create faculties.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the faculty.
     */
    public function update(User $user, Faculty $faculty): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the faculty.
     *
     * A faculty that still owns departments may not be removed, because the
     * departments table restricts deletion of its parent.
     */
    public function delete(User $user, Faculty $faculty): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $faculty->departments()->exists()
            ? Response::deny(__('This faculty still has departments and cannot be deleted.'))
            : Response::allow();
    }
}
