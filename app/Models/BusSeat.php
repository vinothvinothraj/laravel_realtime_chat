<?php

namespace App\Models;

use App\Enums\SeatStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'seat_number',
        'row_number',
        'seat_position',
        'status',
        'is_window',
        'is_aisle',
    ];

    protected function casts(): array
    {
        return [
            'status' => SeatStatus::class,
            'row_number' => 'integer',
            'is_window' => 'boolean',
            'is_aisle' => 'boolean',
        ];
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function tripSeats(): HasMany
    {
        return $this->hasMany(BusTripSeat::class, 'bus_seat_id');
    }
}
