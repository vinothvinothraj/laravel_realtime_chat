<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_operator_id',
        'code',
        'origin',
        'destination',
        'duration_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(BusOperator::class, 'bus_operator_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(BusTrip::class);
    }
}
