<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine whether the user can list students.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    /**
     * Determine whether the user can view the student.
     *
     * Instructors may only see students enrolled in a course they teach, and a
     * student may only see their own record.
     */
    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return (bool) $user->instructor?->teachesStudent($student);
        }

        return $this->owns($user, $student);
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the full student record.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can edit the contact details on the student record.
     *
     * Students may maintain their own contact information, but not the fields
     * that drive their academic standing.
     */
    public function updateProfile(User $user, Student $student): bool
    {
        return $user->isAdmin() || $this->owns($user, $student);
    }

    /**
     * Determine whether the user can delete the student.
     *
     * Deleting students is reserved for administrators.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the student record belongs to the given user.
     */
    private function owns(User $user, Student $student): bool
    {
        return $user->isStudent() && $student->user_id === $user->id;
    }
}
