<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'participant_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the participant associated with the user.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the form submissions for the user through their participant.
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasManyThrough(FormSubmission::class, Participant::class, 'id', 'participant_id', 'participant_id');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if the user has submitted a specific form.
     */
    public function hasSubmittedForm(int $formId): bool
    {
        if (!$this->participant) {
            return false;
        }
        
        return $this->participant->formSubmissions()->where('form_id', $formId)->exists();
    }
}
