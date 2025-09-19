<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'session';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cycle_id',
        'name',
        'session_date',
        'start_time',
        'end_time',
        'location',
        'max_participants',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the cycle that owns the session.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Get the attendances for the session.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope a query to only include active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include sessions for a specific cycle.
     */
    public function scopeForCycle($query, $cycleId)
    {
        return $query->where('cycle_id', $cycleId);
    }

    /**
     * Scope a query to only include sessions within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('session_date', [$startDate, $endDate]);
    }

    /**
     * Get the duration of the session in minutes.
     */
    public function getDurationAttribute(): int
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    /**
     * Check if the session is full.
     */
    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        $currentAttendances = $this->attendances()
                                 ->where('status', 'PRESENT')
                                 ->count();

        return $currentAttendances >= $this->max_participants;
    }

    /**
     * Get available spots for the session.
     */
    public function getAvailableSpotsAttribute(): int
    {
        if (!$this->max_participants) {
            return PHP_INT_MAX;
        }

        $currentAttendances = $this->attendances()
                                 ->where('status', 'PRESENT')
                                 ->count();

        return max(0, $this->max_participants - $currentAttendances);
    }
}