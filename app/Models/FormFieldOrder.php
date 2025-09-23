<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormFieldOrder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'form_fields_order';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'form_id',
        'form_category_id',
        'field_json_id',
        'order',
        'extra_data',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'form_id' => 'integer',
            'form_category_id' => 'integer',
            'field_json_id' => 'integer',
            'order' => 'integer',
            'extra_data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the form that owns the field order.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    /**
     * Get the form category that owns the field order.
     */
    public function formCategory(): BelongsTo
    {
        return $this->belongsTo(FormCategory::class, 'form_category_id');
    }

    /**
     * Get the field JSON that owns the field order.
     */
    public function fieldJson(): BelongsTo
    {
        return $this->belongsTo(FieldJson::class, 'field_json_id');
    }

    /**
     * Scope a query to order by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope a query to only include field orders for a specific form.
     */
    public function scopeForForm($query, $formId)
    {
        return $query->where('form_id', $formId);
    }

    /**
     * Scope a query to only include field orders for a specific category.
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('form_category_id', $categoryId);
    }
}
