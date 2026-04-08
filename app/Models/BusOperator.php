<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusOperator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'contact_phone',
        'contact_email',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function routes(): HasMany
    {
        return $this->hasMany(BusRoute::class);
    }

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}
