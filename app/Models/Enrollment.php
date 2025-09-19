<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'enrollment';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'participant_id',
        'cycle_id',
        'enrollment_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the participant that owns the enrollment.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the cycle that owns the enrollment.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Get the attendances for the enrollment.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope a query to only include enrollments with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include confirmed enrollments.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }

    /**
     * Scope a query to only include pending enrollments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    /**
     * Scope a query to only include completed enrollments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    /**
     * Scope a query to only include cancelled enrollments.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }

    /**
     * Get the attendance rate for this enrollment.
     */
    public function getAttendanceRateAttribute(): float
    {
        $totalSessions = $this->cycle->sessions()->count();
        if ($totalSessions === 0) {
            return 0;
        }

        $attendedSessions = $this->attendances()
                               ->whereIn('status', ['PRESENT', 'LATE'])
                               ->count();

        return ($attendedSessions / $totalSessions) * 100;
    }

    /**
     * Check if the enrollment is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['PENDING', 'CONFIRMED']);
    }

    /**
     * Check if the enrollment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    /**
     * Check if the enrollment is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }
}