<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'present';
    case Absent = 'absent';
    case Late = 'late';
    case Excused = 'excused';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /**
     * Determine whether this status counts as the student having attended.
     */
    public function countsAsAttended(): bool
    {
        return $this === self::Present || $this === self::Late;
    }

    /**
     * The statuses that count towards the attendance percentage.
     *
     * @return array<int, string>
     */
    public static function attended(): array
    {
        return array_column(
            array_filter(self::cases(), fn (self $status): bool => $status->countsAsAttended()),
            'value',
        );
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
