<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Instructor = 'instructor';
    case Student = 'student';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Instructor => 'Instructor',
            self::Student => 'Student',
        };
    }

    /**
     * Get the name of the dashboard route for this role.
     */
    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Instructor => 'instructor.dashboard',
            self::Student => 'student.dashboard',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
