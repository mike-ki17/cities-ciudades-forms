<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'attendance';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'enrollment_id',
        'session_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'check_in_time' => 'datetime:H:i',
            'check_out_time' => 'datetime:H:i',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the enrollment that owns the attendance.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Get the session that owns the attendance.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Scope a query to only include attendances with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include present attendances.
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'PRESENT');
    }

    /**
     * Scope a query to only include absent attendances.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'ABSENT');
    }

    /**
     * Scope a query to only include late attendances.
     */
    public function scopeLate($query)
    {
        return $query->where('status', 'LATE');
    }

    /**
     * Scope a query to only include excused attendances.
     */
    public function scopeExcused($query)
    {
        return $query->where('status', 'EXCUSED');
    }

    /**
     * Scope a query to only include attendances within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Get the duration of attendance in minutes.
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $checkIn = \Carbon\Carbon::parse($this->check_in_time);
        $checkOut = \Carbon\Carbon::parse($this->check_out_time);
        return $checkIn->diffInMinutes($checkOut);
    }

    /**
     * Check if the attendance is present.
     */
    public function isPresent(): bool
    {
        return in_array($this->status, ['PRESENT', 'LATE']);
    }

    /**
     * Check if the attendance is absent.
     */
    public function isAbsent(): bool
    {
        return $this->status === 'ABSENT';
    }

    /**
     * Check if the attendance is late.
     */
    public function isLate(): bool
    {
        return $this->status === 'LATE';
    }

    /**
     * Check if the attendance is excused.
     */
    public function isExcused(): bool
    {
        return $this->status === 'EXCUSED';
    }
}