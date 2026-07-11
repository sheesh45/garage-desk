<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case ServiceAdvisor = 'service_advisor';
    case Mechanic = 'mechanic';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
