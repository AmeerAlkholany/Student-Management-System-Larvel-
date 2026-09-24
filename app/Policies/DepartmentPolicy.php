<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the user can list departments.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the department.
     */
    public function view(User $user, Department $department): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create departments.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the department.
     */
    public function update(User $user, Department $department): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the department.
     *
     * Students, courses and instructors all restrict deletion of their
     * department, so the check is made explicit here.
     */
    public function delete(User $user, Department $department): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        $hasDependents = $department->students()->exists()
            || $department->courses()->exists()
            || $department->instructors()->exists();

        return $hasDependents
            ? Response::deny(__('This department still has related records and cannot be deleted.'))
            : Response::allow();
    }
}
