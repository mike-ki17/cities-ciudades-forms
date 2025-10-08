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
        'event_id',
        'name',
        'slug',
        'description',
        'schema_json',
        'style_json',
        'validation_config',
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
            'validation_config' => 'array',
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
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the city through the event relationship (convenience method).
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the form submissions for the form.
     */
    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    /**
     * Get the form field orders for the form.
     */
    public function formFieldOrders(): HasMany
    {
        return $this->hasMany(FormFieldOrder::class, 'form_id');
    }

    /**
     * Get the form categories for the form.
     */
    public function formCategories(): HasMany
    {
        return $this->hasManyThrough(
            FormCategory::class,
            FormFieldOrder::class,
            'form_id',
            'id',
            'id',
            'form_category_id'
        )->distinct();
    }

    /**
     * Scope a query to only include active forms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include forms for a specific event.
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
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
     * Get the form fields from schema_json (legacy method).
     */
    public function getFieldsAttribute(): array
    {
        return $this->schema_json['fields'] ?? [];
    }

    /**
     * Get the form fields from relational structure.
     */
    public function getRelationalFields(): \Illuminate\Support\Collection
    {
        return $this->formFieldOrders()
            ->with(['fieldJson', 'formCategory.formOptions'])
            ->ordered()
            ->get()
            ->map(function ($fieldOrder) {
                $field = $fieldOrder->fieldJson;
                $category = $fieldOrder->formCategory;
                
                // Get options directly from the loaded relationship
                $options = [];
                if ($category && $category->relationLoaded('formOptions')) {
                    $options = $category->formOptions
                        ->where('is_active', true)
                        ->sortBy('order')
                        ->map(function ($option) {
                            return [
                                'value' => $option->value,
                                'label' => $option->label,
                                'description' => $option->description,
                            ];
                        })
                        ->values()
                        ->toArray();
                }
                
                // Handle visible field correctly - get raw value and decode if needed
                $visibleValue = $field->getRawOriginal('visible');
                $visible = null;
                if (!is_null($visibleValue) && $visibleValue !== '') {
                    $visible = is_string($visibleValue) ? json_decode($visibleValue, true) : $visibleValue;
                }
                
                return [
                    'id' => $field->id,
                    'key' => $field->key,
                    'label' => $field->label,
                    'type' => $field->type,
                    'required' => $field->required,
                    'placeholder' => $field->placeholder,
                    'validations' => $field->validations ?? [],
                    'visible' => $visible,
                    'default_value' => $field->default_value,
                    'description' => $field->description,
                    'order' => $fieldOrder->order,
                    'category' => $category ? [
                        'id' => $category->id,
                        'code' => $category->code,
                        'name' => $category->name,
                    ] : null,
                    'options' => $options,
                    'extra_data' => $fieldOrder->extra_data,
                ];
            });
    }

    /**
     * Get options for a specific field from the relational structure.
     */
    private function getFieldOptions(string $fieldKey, ?FormCategory $category): array
    {
        if (!$category) {
            return [];
        }

        return $category->formOptions()
            ->active()
            ->ordered()
            ->get()
            ->map(function ($option) {
                return [
                    'value' => $option->value,
                    'label' => $option->label,
                    'description' => $option->description,
                ];
            })
            ->toArray();
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

                case 'tel':
                    $fieldRules[] = 'string';
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

                case 'file':
                    // For file fields, we don't validate as files during form submission
                    // because the file is already uploaded and we only have JSON info
                    $fieldRules = ['nullable', 'string'];
                    \Log::info('File field validation rules generated in Form model', [
                        'field_key' => $field['key'] ?? 'unknown',
                        'rules' => $fieldRules
                    ]);
                    break;

                case 'section':
                    // Los campos de sección no se validan como campos de entrada
                    $fieldRules = ['nullable'];
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
        // Note: File validation is handled separately during file upload, not during form submission
        // if (isset($validations['file_types'])) {
        //     $types = is_array($validations['file_types']) 
        //         ? implode(',', $validations['file_types'])
        //         : $validations['file_types'];
        //     $fieldRules[] = 'mimes:' . $types;
        // }
    }

    /**
     * Convert relational fields to JSON format for editing.
     * This method creates a JSON structure compatible with the form creation interface.
     */
    public function getRelationalFieldsAsJson(): array
    {
        try {
            $relationalFields = $this->getRelationalFields();
            
            if ($relationalFields->isEmpty()) {
                // If no relational fields, return empty structure
                return ['fields' => []];
            }

            $fields = [];
            
            foreach ($relationalFields as $field) {
                $fieldData = [
                    'key' => $field['key'],
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'required' => $field['required'] ?? false,
                    'order' => $field['order'] ?? 1,
                ];

                // Add optional properties if they exist
                if (!empty($field['placeholder'])) {
                    $fieldData['placeholder'] = $field['placeholder'];
                }

                if (!empty($field['description'])) {
                    $fieldData['description'] = $field['description'];
                }

                if (!empty($field['default_value'])) {
                    $fieldData['default_value'] = $field['default_value'];
                }

                if (!empty($field['validations'])) {
                    $fieldData['validations'] = $field['validations'];
                }

                if (!empty($field['visible'])) {
                    $fieldData['visible'] = $field['visible'];
                }

                // Add options for select and checkbox fields
                if (!empty($field['options']) && in_array($field['type'], ['select', 'checkbox'])) {
                    $fieldData['options'] = $field['options'];
                }

                $fields[] = $fieldData;
            }

            return ['fields' => $fields];
            
        } catch (\Exception $e) {
            // If there's an error getting relational fields, return empty structure
            \Log::warning('Error converting relational fields to JSON', [
                'form_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            
            return ['fields' => []];
        }
    }

    /**
     * Get the appropriate schema JSON for editing.
     * Returns relational fields as JSON if available, otherwise returns legacy schema_json.
     */
    public function getSchemaJsonForEditing(): array
    {
        // First try to get relational fields as JSON
        $relationalJson = $this->getRelationalFieldsAsJson();
        
        // If we have relational fields, use them
        if (!empty($relationalJson['fields'])) {
            return $relationalJson;
        }
        
        // Fallback to legacy schema_json
        return $this->schema_json ?? ['fields' => []];
    }

    /**
     * Get the validation configuration for fixed fields.
     */
    public function getValidationConfigAttribute(): array
    {
        $defaults = $this->getDefaultValidationConfig();
        $custom = $this->validation_config ?? [];
        
        // Merge custom config with defaults, giving priority to custom values
        return array_merge($defaults, $custom);
    }

    /**
     * Get default validation configuration for fixed fields.
     */
    public function getDefaultValidationConfig(): array
    {
        return [
            'participant_fields' => [
                'name' => [
                    'min_length' => 2,
                    'max_length' => 255,
                    'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$',
                    'trim_spaces' => true,
                ],
                'email' => [
                    'max_length' => 255,
                    'trim_spaces' => true,
                ],
                'phone' => [
                    'pattern' => '^[0-9]{7,12}$',
                    'trim_spaces' => true,
                ],
                'document_type' => [
                    'allowed_types' => ['CC', 'TI', 'CE', 'NIT', 'PASAPORTE', 'RC', 'PEP', 'PPT', 'OTRO'],
                ],
                'document_number' => [
                    'max_length' => 50,
                    'trim_spaces' => true,
                    'uppercase' => true,
                ],
                'birth_date' => [
                    'min_age' => 18,
                    'max_age' => null,
                    'before_today' => true,
                ],
            ],
        ];
    }

    /**
     * Get validation rules for fixed participant fields based on form configuration.
     */
    public function getFixedFieldValidationRules(): array
    {
        $config = $this->getValidationConfigAttribute();
        $participantConfig = $config['participant_fields'] ?? [];
        
        $rules = [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'document_type' => ['required', 'string'],
            'document_number' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
        ];

        // Apply name validations
        if (isset($participantConfig['name'])) {
            $nameConfig = $participantConfig['name'];
            if (isset($nameConfig['min_length'])) {
                $rules['name'][] = 'min:' . $nameConfig['min_length'];
            }
            if (isset($nameConfig['max_length'])) {
                $rules['name'][] = 'max:' . $nameConfig['max_length'];
            }
            if (isset($nameConfig['pattern'])) {
                $rules['name'][] = 'regex:/' . $nameConfig['pattern'] . '/';
            }
        }

        // Apply email validations
        if (isset($participantConfig['email'])) {
            $emailConfig = $participantConfig['email'];
            if (isset($emailConfig['max_length'])) {
                $rules['email'][] = 'max:' . $emailConfig['max_length'];
            }
        }

        // Apply phone validations
        if (isset($participantConfig['phone'])) {
            $phoneConfig = $participantConfig['phone'];
            if (isset($phoneConfig['pattern'])) {
                $rules['phone'][] = 'regex:/' . $phoneConfig['pattern'] . '/';
            }
        }

        // Apply document type validations
        if (isset($participantConfig['document_type'])) {
            $docTypeConfig = $participantConfig['document_type'];
            if (isset($docTypeConfig['allowed_types'])) {
                $rules['document_type'][] = 'in:' . implode(',', $docTypeConfig['allowed_types']);
            }
        }

        // Apply document number validations
        if (isset($participantConfig['document_number'])) {
            $docNumberConfig = $participantConfig['document_number'];
            if (isset($docNumberConfig['max_length'])) {
                $rules['document_number'][] = 'max:' . $docNumberConfig['max_length'];
            }
            // Add custom validation for document number format
            $rules['document_number'][] = function ($attribute, $value, $fail) {
                $documentType = request()->input('document_type');
                if (!$this->validateDocumentNumber($documentType, $value)) {
                    $fail($this->getDocumentValidationMessage($documentType));
                }
            };
        }

        // Apply birth date validations
        if (isset($participantConfig['birth_date'])) {
            $birthConfig = $participantConfig['birth_date'];
            
            if (isset($birthConfig['before_today']) && $birthConfig['before_today']) {
                $rules['birth_date'][] = 'before:today';
            }
            
            if (isset($birthConfig['min_age'])) {
                $maxDate = now()->subYears($birthConfig['min_age'])->format('Y-m-d');
                $rules['birth_date'][] = 'before_or_equal:' . $maxDate;
            }
            
            if (isset($birthConfig['max_age'])) {
                $minDate = now()->subYears($birthConfig['max_age'])->format('Y-m-d');
                $rules['birth_date'][] = 'after_or_equal:' . $minDate;
            }
        }

        return $rules;
    }

    /**
     * Validate document number based on document type (moved from Request).
     */
    public function validateDocumentNumber(string $documentType, string $documentNumber): bool
    {
        // Clean the document number (remove spaces and convert to uppercase)
        $cleanNumber = strtoupper(trim($documentNumber));
        
        switch ($documentType) {
            case 'CC': // Cédula de Ciudadanía
                return preg_match('/^[0-9]{6,10}$/', $cleanNumber);
                
            case 'TI': // Tarjeta de Identidad
                return preg_match('/^[0-9]{6,11}$/', $cleanNumber);
                
            case 'CE': // Cédula de Extranjería
                return preg_match('/^[0-9]{6,15}$/', $cleanNumber);
                
            case 'NIT': // Número de Identificación Tributaria
                return preg_match('/^[0-9]{9,15}(-[0-9])?$/', $cleanNumber);
                
            case 'PASAPORTE': // Pasaporte
                return preg_match('/^[A-Z0-9]{6,12}$/', $cleanNumber);
                
            case 'RC': // Registro Civil
                return preg_match('/^[0-9]{10,15}$/', $cleanNumber);
                
            case 'PEP': // Permiso Especial de Permanencia
            case 'PPT': // Permiso por Protección Temporal
                return preg_match('/^[A-Z0-9]{6,15}$/', $cleanNumber);
                
            case 'OTRO': // Otros tipos (NUIP, Carné Diplomático, etc.)
                return preg_match('/^[A-Z0-9]{6,15}$/', $cleanNumber);
                
            default:
                return false;
        }
    }

    /**
     * Get validation message for document type (moved from Request).
     */
    public function getDocumentValidationMessage(string $documentType): string
    {
        switch ($documentType) {
            case 'CC':
                return 'La Cédula de Ciudadanía debe contener solo números y tener entre 6 y 10 dígitos.';
            case 'TI':
                return 'La Tarjeta de Identidad debe contener solo números y tener entre 6 y 11 dígitos.';
            case 'CE':
                return 'La Cédula de Extranjería debe contener solo números y tener entre 6 y 15 dígitos.';
            case 'NIT':
                return 'El NIT debe contener entre 9 y 15 dígitos y puede incluir un dígito de verificación separado por guión (ej: 900123456-7).';
            case 'PASAPORTE':
                return 'El Pasaporte debe contener entre 6 y 12 caracteres alfanuméricos (letras y números).';
            case 'RC':
                return 'El Registro Civil debe contener solo números y tener entre 10 y 15 dígitos.';
            case 'PEP':
                return 'El PEP debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            case 'PPT':
                return 'El PPT debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            case 'OTRO':
                return 'El documento debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            default:
                return 'El formato del número de documento no es válido.';
        }
    }
}