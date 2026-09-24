<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

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
