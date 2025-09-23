<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'city',
        'year',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the forms for the event.
     */
    public function forms(): HasMany
    {
        return $this->hasMany(Form::class, 'event_id');
    }

    /**
     * Get the participants for the event through cycles and attendances.
     * This is a complex relationship that requires custom queries.
     */
    public function getParticipantsCountAttribute()
    {
        return \Illuminate\Support\Facades\DB::table('participants')
            ->join('attendances', 'participants.id', '=', 'attendances.participant_id')
            ->join('cycles', 'attendances.cycle_id', '=', 'cycles.id')
            ->where('cycles.events_id', $this->id)
            ->distinct('participants.id')
            ->count();
    }

    /**
     * Get the cycles for the event.
     */
    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class, 'events_id');
    }

    /**
     * Scope a query to only include events by name (case insensitive).
     */
    public function scopeByNameInsensitive($query, string $name)
    {
        return $query->whereRaw('LOWER(name) = ?', [strtolower($name)]);
    }

    /**
     * Scope a query to only include events by city (case insensitive).
     */
    public function scopeByCityInsensitive($query, string $city)
    {
        return $query->whereRaw('LOWER(city) = ?', [strtolower($city)]);
    }

    /**
     * Scope a query to only include events by year.
     */
    public function scopeByYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Get the active forms for the event.
     */
    public function activeForms(): HasMany
    {
        return $this->forms()->where('is_active', true);
    }

    /**
     * Get the full event name (name - city, year).
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} - {$this->city}, {$this->year}";
    }
}