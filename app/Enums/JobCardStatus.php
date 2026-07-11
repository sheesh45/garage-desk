<?php

namespace App\Enums;

enum JobCardStatus: string
{
    case Pending = 'pending';
    case Inspection = 'inspection';
    case WaitingParts = 'waiting_parts';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Delivered = 'delivered';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
