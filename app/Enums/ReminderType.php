<?php

namespace App\Enums;

enum ReminderType: string
{
    case Service = 'service';
    case Insurance = 'insurance';
    case Puc = 'puc';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
