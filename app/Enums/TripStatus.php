<?php

namespace App\Enums;

enum TripStatus: string
{
    case Scheduled = 'scheduled';
    case Boarding = 'boarding';
    case Departed = 'departed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
