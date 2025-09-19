<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'participants';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'document_type',
        'document_number',
        'birth_date',
        'city_id',
        'extra_data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the city that owns the participant.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the form submissions for the participant.
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * Get the enrollments for the participant.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the user associated with the participant.
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the full name of the participant.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the first name of the participant.
     */
    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->name)[0] ?? $this->name;
    }

    /**
     * Get the last name of the participant.
     */
    public function getLastNameAttribute(): string
    {
        $nameParts = explode(' ', $this->name);
        return count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
    }

    /**
     * Scope a query to only include participants by email (case insensitive).
     */
    public function scopeByEmailInsensitive($query, string $email)
    {
        return $query->whereRaw('LOWER(email) = ?', [strtolower($email)]);
    }

    /**
     * Scope a query to only include participants by document.
     */
    public function scopeByDocument($query, string $documentType, string $documentNumber)
    {
        return $query->where('document_type', $documentType)
                    ->where('document_number', $documentNumber);
    }

    /**
     * Scope a query to only include participants for a specific city.
     */
    public function scopeForCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Check if participant has submitted a specific form.
     */
    public function hasSubmittedForm(int $formId): bool
    {
        return $this->formSubmissions()
                   ->where('form_id', $formId)
                   ->exists();
    }

    /**
     * Get the latest submission for a specific form.
     */
    public function getLatestSubmissionForForm(int $formId): ?FormSubmission
    {
        return $this->formSubmissions()
                   ->where('form_id', $formId)
                   ->latest('submitted_at')
                   ->first();
    }
}