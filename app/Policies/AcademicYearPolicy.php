<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcademicYearPolicy
{
    /**
     * Determine whether the user can list academic years.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the academic year.
     */
    public function view(User $user, AcademicYear $academicYear): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create academic years.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the academic year.
     */
    public function update(User $user, AcademicYear $academicYear): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the academic year.
     */
    public function delete(User $user, AcademicYear $academicYear): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        return $academicYear->semesters()->exists()
            ? Response::deny(__('This academic year still has semesters and cannot be deleted.'))
            : Response::allow();
    }
}
