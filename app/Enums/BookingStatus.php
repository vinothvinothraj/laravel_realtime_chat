<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Draft = 'draft';
    case SeatHeld = 'seat_held';
    case PaymentPending = 'payment_pending';
    case Confirmed = 'confirmed';
    case CheckedIn = 'checked_in';
    case Boarded = 'boarded';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::SeatHeld => 'Seat Held',
            self::PaymentPending => 'Payment Pending',
            self::Confirmed => 'Confirmed',
            self::CheckedIn => 'Checked In',
            self::Boarded => 'Boarded',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Expired => 'Expired',
            self::Refunded => 'Refunded',
        };
    }
}
