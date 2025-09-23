<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'form_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the form options for the category.
     */
    public function formOptions(): HasMany
    {
        return $this->hasMany(FormOption::class, 'category_id');
    }

    /**
     * Get the form field orders for the category.
     */
    public function formFieldOrders(): HasMany
    {
        return $this->hasMany(FormFieldOrder::class, 'form_category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to find a category by code.
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}
