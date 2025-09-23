<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldJson extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'fields_json';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'key',
        'label',
        'type',
        'required',
        'placeholder',
        'validations',
        'visible',
        'default_value',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'validations' => 'array',
            'visible' => 'array',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the form field orders that use this field.
     */
    public function formFieldOrders(): HasMany
    {
        return $this->hasMany(FormFieldOrder::class, 'field_json_id');
    }

    /**
     * Scope a query to only include active fields.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to find a field by key.
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope a query to filter by field type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
