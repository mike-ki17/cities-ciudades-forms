<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'form_submission';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'form_id',
        'participant_id',
        'data_json',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'data_json' => 'array',
            'submitted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the form that owns the submission.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the participant that owns the submission.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Scope a query to only include submissions for a specific form.
     */
    public function scopeForForm($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    /**
     * Scope a query to only include submissions for a specific participant.
     */
    public function scopeForParticipant($query, $participantId)
    {
        return $query->where('participant_id', $participantId);
    }

    /**
     * Scope a query to only include submissions within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('submitted_at', [$startDate, $endDate]);
    }

    /**
     * Get a specific field value from the submission data.
     */
    public function getFieldValue(string $fieldKey): mixed
    {
        return $this->data_json[$fieldKey] ?? null;
    }

    /**
     * Set a specific field value in the submission data.
     */
    public function setFieldValue(string $fieldKey, mixed $value): void
    {
        $data = $this->data_json ?? [];
        $data[$fieldKey] = $value;
        $this->data_json = $data;
    }

    /**
     * Get all field values as a formatted array.
     */
    public function getFormattedData(): array
    {
        $formatted = [];
        $form = $this->form;
        
        if (!$form) {
            return $this->data_json ?? [];
        }

        $fields = $form->getFieldsAttribute();
        
        foreach ($fields as $field) {
            $key = $field['key'];
            $value = $this->getFieldValue($key);
            
            $formatted[] = [
                'label' => $field['label'],
                'key' => $key,
                'type' => $field['type'],
                'value' => $value,
            ];
        }

        return $formatted;
    }
}