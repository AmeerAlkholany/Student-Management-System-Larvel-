<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Enrolled = 'enrolled';
    case Completed = 'completed';
    case Dropped = 'dropped';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
