<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'forms';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'description',
        'schema_json',
        'style_json',
        'is_active',
        'version',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'schema_json' => 'array',
            'style_json' => 'array',
            'is_active' => 'boolean',
            'version' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the event that owns the form.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'city_id');
    }

    /**
     * Get the city that owns the form (alias for backward compatibility).
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'city_id');
    }

    /**
     * Get the form submissions for the form.
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * Scope a query to only include active forms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include forms for a specific city.
     */
    public function scopeForCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }


    /**
     * Get the latest version of the form.
     */
    public function scopeLatestVersion($query)
    {
        return $query->orderBy('version', 'desc');
    }

    /**
     * Scope a query to find a form by slug.
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Generate a unique slug for the form.
     */
    public function generateSlug(): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Boot method to automatically generate slug when creating/updating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = $form->generateSlug();
            }
        });

        static::updating(function ($form) {
            if ($form->isDirty('name') && empty($form->slug)) {
                $form->slug = $form->generateSlug();
            }
        });
    }

    /**
     * Get the form fields from schema_json.
     */
    public function getFieldsAttribute(): array
    {
        return $this->schema_json['fields'] ?? [];
    }

    /**
     * Get the form styles from style_json.
     */
    public function getStylesAttribute(): array
    {
        $defaults = $this->getDefaultStyles();
        $custom = $this->style_json ?? [];
        
        // Merge custom styles with defaults, giving priority to custom values
        return array_merge($defaults, $custom);
    }

    /**
     * Get default style configuration.
     */
    public function getDefaultStyles(): array
    {
        return [
            'header_image_1' => null,
            'header_image_2' => null,
            'background_color' => '#ffffff',
            'background_texture' => null,
            'primary_color' => '#00ffbd',
            'border_radius' => '8px',
            'form_shadow' => true,
        ];
    }

    /**
     * Check if the form has a specific field.
     */
    public function hasField(string $fieldKey): bool
    {
        $fields = $this->getFieldsAttribute();
        return collect($fields)->contains('key', $fieldKey);
    }

    /**
     * Get validation rules for the form fields.
     */
    public function getValidationRules(array $formData = []): array
    {
        $rules = [];
        $fields = $this->getFieldsAttribute();

        foreach ($fields as $field) {
            $fieldRules = [];

            // Check if field is conditionally visible
            $isVisible = $this->isFieldVisible($field, $formData);

            // Campo requerido (solo si es visible)
            if (($field['required'] ?? false) && $isVisible) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Validaciones específicas por tipo
            switch ($field['type']) {
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'string';
                    $this->addTextValidations($fieldRules, $field);
                    break;

                case 'number':
                    $fieldRules[] = 'numeric';
                    $this->addNumericValidations($fieldRules, $field);
                    break;

                case 'email':
                    $fieldRules[] = 'email';
                    $this->addTextValidations($fieldRules, $field);
                    break;

                case 'date':
                    $fieldRules[] = 'date';
                    $this->addDateValidations($fieldRules, $field);
                    break;

                case 'select':
                    $this->addSelectValidations($fieldRules, $field);
                    break;

                case 'checkbox':
                    $this->addCheckboxValidations($fieldRules, $field);
                    break;
            }

            // Validaciones personalizadas
            $this->addCustomValidations($fieldRules, $field);

            $rules[$field['key']] = $fieldRules;
        }

        return $rules;
    }

    /**
     * Check if a field should be visible based on conditional logic.
     */
    public function isFieldVisible(array $field, array $formData = []): bool
    {
        // If no visibility condition is set, field is always visible
        if (!isset($field['visible'])) {
            return true;
        }

        $visible = $field['visible'];
        $modelField = $visible['model'] ?? null;
        $expectedValue = $visible['value'] ?? null;
        $condition = $visible['condition'] ?? 'equal';

        // If the model field doesn't exist in form data, field is not visible
        if (!isset($formData[$modelField])) {
            return false;
        }

        $actualValue = $formData[$modelField];

        // Apply the condition
        switch ($condition) {
            case 'equal':
                return $actualValue == $expectedValue;
            case 'not_equal':
                return $actualValue != $expectedValue;
            case 'contains':
                return is_string($actualValue) && str_contains($actualValue, $expectedValue);
            case 'not_contains':
                return is_string($actualValue) && !str_contains($actualValue, $expectedValue);
            case 'greater_than':
                return is_numeric($actualValue) && is_numeric($expectedValue) && $actualValue > $expectedValue;
            case 'less_than':
                return is_numeric($actualValue) && is_numeric($expectedValue) && $actualValue < $expectedValue;
            case 'greater_equal':
                return is_numeric($actualValue) && is_numeric($expectedValue) && $actualValue >= $expectedValue;
            case 'less_equal':
                return is_numeric($actualValue) && is_numeric($expectedValue) && $actualValue <= $expectedValue;
            default:
                return false;
        }
    }

    /**
     * Add text validations (min/max length, regex, etc.)
     */
    private function addTextValidations(array &$fieldRules, array $field): void
    {
        $validations = $field['validations'] ?? [];

        // Longitud mínima
        if (isset($validations['min_length'])) {
            $fieldRules[] = 'min:' . $validations['min_length'];
        }

        // Longitud máxima
        if (isset($validations['max_length'])) {
            $fieldRules[] = 'max:' . $validations['max_length'];
        }

        // Patrón regex
        if (isset($validations['pattern'])) {
            $fieldRules[] = 'regex:/' . $validations['pattern'] . '/';
        }

        // Solo letras
        if ($validations['letters_only'] ?? false) {
            $fieldRules[] = 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/';
        }

        // Solo números
        if ($validations['numbers_only'] ?? false) {
            $fieldRules[] = 'regex:/^[0-9]+$/';
        }

        // Alfanumérico
        if ($validations['alphanumeric'] ?? false) {
            $fieldRules[] = 'regex:/^[a-zA-Z0-9]+$/';
        }
    }

    /**
     * Add numeric validations (min/max value, integer, etc.)
     */
    private function addNumericValidations(array &$fieldRules, array $field): void
    {
        $validations = $field['validations'] ?? [];

        // Valor mínimo
        if (isset($validations['min_value'])) {
            $fieldRules[] = 'min:' . $validations['min_value'];
        }

        // Valor máximo
        if (isset($validations['max_value'])) {
            $fieldRules[] = 'max:' . $validations['max_value'];
        }

        // Solo enteros
        if ($validations['integer_only'] ?? false) {
            $fieldRules[] = 'integer';
        }

        // Número positivo
        if ($validations['positive'] ?? false) {
            $fieldRules[] = 'min:0';
        }

        // Número negativo
        if ($validations['negative'] ?? false) {
            $fieldRules[] = 'max:0';
        }

        // Máximo de dígitos (para campos de documento)
        if (isset($validations['max_digits'])) {
            $fieldRules[] = function ($attribute, $value, $fail) use ($validations) {
                if (is_numeric($value)) {
                    $digitCount = strlen((string)$value);
                    if ($digitCount > $validations['max_digits']) {
                        $fail("El campo {$attribute} no puede tener más de {$validations['max_digits']} números.");
                    }
                }
            };
        }
    }

    /**
     * Add date validations (date range, age restrictions, etc.)
     */
    private function addDateValidations(array &$fieldRules, array $field): void
    {
        $validations = $field['validations'] ?? [];

        // Fecha mínima
        if (isset($validations['min_date'])) {
            $fieldRules[] = 'after_or_equal:' . $validations['min_date'];
        }

        // Fecha máxima
        if (isset($validations['max_date'])) {
            $fieldRules[] = 'before_or_equal:' . $validations['max_date'];
        }

        // Edad mínima
        if (isset($validations['min_age'])) {
            $maxDate = now()->subYears($validations['min_age'])->format('Y-m-d');
            $fieldRules[] = 'before_or_equal:' . $maxDate;
        }

        // Edad máxima
        if (isset($validations['max_age'])) {
            $minDate = now()->subYears($validations['max_age'])->format('Y-m-d');
            $fieldRules[] = 'after_or_equal:' . $minDate;
        }

        // Rango de fechas específico
        if (isset($validations['date_range'])) {
            $range = $validations['date_range'];
            if (isset($range['start']) && isset($range['end'])) {
                $fieldRules[] = 'after_or_equal:' . $range['start'];
                $fieldRules[] = 'before_or_equal:' . $range['end'];
            }
        }
    }

    /**
     * Add select validations
     */
    private function addSelectValidations(array &$fieldRules, array $field): void
    {
        if (isset($field['options'])) {
            $allowedValues = [];
            foreach ($field['options'] as $option) {
                if (is_array($option)) {
                    $allowedValues[] = $option['value'];
                } else {
                    $allowedValues[] = $option;
                }
            }
            $fieldRules[] = 'in:' . implode(',', $allowedValues);
        }
    }

    /**
     * Add checkbox validations
     */
    private function addCheckboxValidations(array &$fieldRules, array $field): void
    {
        if (isset($field['options']) && is_array($field['options']) && count($field['options']) > 0) {
            // Múltiples checkboxes
            $fieldRules[] = 'array';
            
            $allowedValues = [];
            foreach ($field['options'] as $option) {
                $allowedValues[] = is_array($option) ? $option['value'] : $option;
            }
            $fieldRules[] = 'in:' . implode(',', $allowedValues);

            // Número mínimo de selecciones
            $validations = $field['validations'] ?? [];
            if (isset($validations['min_selections'])) {
                $fieldRules[] = 'min:' . $validations['min_selections'];
            }

            // Número máximo de selecciones
            if (isset($validations['max_selections'])) {
                $fieldRules[] = 'max:' . $validations['max_selections'];
            }
        } else {
            // Checkbox simple
            $fieldRules[] = 'boolean';
        }
    }

    /**
     * Add custom validations
     */
    private function addCustomValidations(array &$fieldRules, array $field): void
    {
        $validations = $field['validations'] ?? [];

        // Validación personalizada con callback
        if (isset($validations['custom_rule'])) {
            $fieldRules[] = $validations['custom_rule'];
        }

        // Validación de máximo de espacios
        if (isset($validations['max_spaces'])) {
            $fieldRules[] = function ($attribute, $value, $fail) use ($validations) {
                if (is_string($value)) {
                    $spaceCount = substr_count($value, ' ');
                    if ($spaceCount > $validations['max_spaces']) {
                        $fail("El campo {$attribute} no puede tener más de {$validations['max_spaces']} espacios.");
                    }
                }
            };
        }

        // Validación de máximo de elementos (caracteres/dígitos)
        if (isset($validations['max_elements'])) {
            $fieldRules[] = function ($attribute, $value, $fail) use ($validations) {
                $elementCount = 0;
                
                if (is_array($value)) {
                    // Para arrays (checkboxes múltiples, campos repetibles)
                    $elementCount = count($value);
                } elseif (is_string($value)) {
                    // Para strings, contar caracteres (incluyendo espacios)
                    $elementCount = strlen($value);
                } elseif (is_numeric($value)) {
                    // Para campos numéricos, contar dígitos
                    $elementCount = strlen((string)$value);
                } elseif (!empty($value)) {
                    // Para otros tipos de datos no vacíos, convertir a string y contar
                    $elementCount = strlen((string)$value);
                }
                
                if ($elementCount > $validations['max_elements']) {
                    $fail("El campo {$attribute} no puede tener más de {$validations['max_elements']} caracteres.");
                }
            };
        }

        // Validación de tamaño de archivo (para futuras implementaciones)
        if (isset($validations['file_size'])) {
            $fieldRules[] = 'max:' . $validations['file_size'];
        }

        // Validación de tipo de archivo (para futuras implementaciones)
        if (isset($validations['file_types'])) {
            $types = is_array($validations['file_types']) 
                ? implode(',', $validations['file_types'])
                : $validations['file_types'];
            $fieldRules[] = 'mimes:' . $types;
        }
    }
}