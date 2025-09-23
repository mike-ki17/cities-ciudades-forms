<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentFormOption extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'parent_form_options';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'form_option_parent_id',
        'form_option_child_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'form_option_parent_id' => 'integer',
            'form_option_child_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent form option.
     */
    public function parentOption(): BelongsTo
    {
        return $this->belongsTo(FormOption::class, 'form_option_parent_id');
    }

    /**
     * Get the child form option.
     */
    public function childOption(): BelongsTo
    {
        return $this->belongsTo(FormOption::class, 'form_option_child_id');
    }
}
