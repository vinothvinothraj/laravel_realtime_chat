<?php

namespace App\Models;

use App\Enums\SeatStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusTripSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_trip_id',
        'bus_seat_id',
        'booking_id',
        'status',
        'held_until',
    ];

    protected function casts(): array
    {
        return [
            'status' => SeatStatus::class,
            'held_until' => 'datetime',
        ];
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(BusTrip::class, 'bus_trip_id');
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(BusSeat::class, 'bus_seat_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
