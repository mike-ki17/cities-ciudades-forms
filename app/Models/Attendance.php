<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'attendances';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'participant_id',
        'cycle_id',
        'attended',
        'attended_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'participant_id' => 'integer',
            'cycle_id' => 'integer',
            'attended' => 'boolean',
            'attended_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the participant that owns the attendance.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    /**
     * Get the cycle that owns the attendance.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

}