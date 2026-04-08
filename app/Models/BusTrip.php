<?php

namespace App\Models;

use App\Enums\TripStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_route_id',
        'bus_id',
        'departure_at',
        'arrival_at',
        'base_fare',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'base_fare' => 'decimal:2',
            'status' => TripStatus::class,
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class, 'bus_route_id');
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(BusTripSeat::class, 'bus_trip_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'bus_trip_id');
    }
}
