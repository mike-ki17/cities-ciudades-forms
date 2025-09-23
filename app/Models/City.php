<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'timezone',
        'extra_data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'extra_data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the events for the city.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'city_id');
    }

    /**
     * Scope a query to only include cities by name (case insensitive).
     */
    public function scopeByNameInsensitive($query, string $name)
    {
        return $query->whereRaw('LOWER(name) = ?', [strtolower($name)]);
    }
}