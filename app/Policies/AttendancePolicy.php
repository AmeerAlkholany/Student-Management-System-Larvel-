<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Determine whether the user can list attendance records.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isInstructor();
    }

    /**
     * Determine whether the user can view the attendance record.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor()) {
            return (bool) $user->instructor?->teaches($attendance->course);
        }

        return $attendance->student->user_id === $user->id;
    }

    /**
     * Determine whether the user can record attendance for the given course.
     */
    public function create(User $user, ?Course $course = null): bool
    {
        if ($course === null) {
            return $user->isAdmin() || $user->isInstructor();
        }

        return $user->isAdmin() || (bool) $user->instructor?->teaches($course);
    }

    /**
     * Determine whether the user can update the attendance record.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() || (bool) $user->instructor?->teaches($attendance->course);
    }

    /**
     * Determine whether the user can delete the attendance record.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin() || (bool) $user->instructor?->teaches($attendance->course);
    }
}
