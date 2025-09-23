<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormOption extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'form_options';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'value',
        'label',
        'order',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the category that owns the form option.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FormCategory::class, 'category_id');
    }

    /**
     * Get the parent options (many-to-many self-referencing).
     */
    public function parentOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            FormOption::class,
            'parent_form_options',
            'form_option_child_id',
            'form_option_parent_id'
        );
    }

    /**
     * Get the child options (many-to-many self-referencing).
     */
    public function childOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            FormOption::class,
            'parent_form_options',
            'form_option_parent_id',
            'form_option_child_id'
        );
    }

    /**
     * Get the parent form options pivot records.
     */
    public function parentFormOptions(): HasMany
    {
        return $this->hasMany(ParentFormOption::class, 'form_option_child_id');
    }

    /**
     * Get the child form options pivot records.
     */
    public function childFormOptions(): HasMany
    {
        return $this->hasMany(ParentFormOption::class, 'form_option_parent_id');
    }

    /**
     * Scope a query to only include active options.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order options by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope a query to only include options for a specific category.
     */
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
