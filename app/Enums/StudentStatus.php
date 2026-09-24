<?php

namespace App\Enums;

enum StudentStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Graduated = 'graduated';
    case Suspended = 'suspended';

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
