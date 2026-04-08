<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_operator_id',
        'name',
        'registration_number',
        'bus_type',
        'seat_capacity',
        'seat_layout',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'seat_capacity' => 'integer',
            'seat_layout' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(BusOperator::class, 'bus_operator_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(BusSeat::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(BusTrip::class);
    }
}
