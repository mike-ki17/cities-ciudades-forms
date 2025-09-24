<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\FormFieldOrder;
use App\Models\FieldJson;
use App\Repositories\EventRepository;
use App\Repositories\FormRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormService
{
    public function __construct(
        private FormRepository $formRepository,
        private EventRepository $eventRepository
    ) {}

    /**
     * Get all active forms for an event.
     */
    public function getActiveFormsForEvent(string $eventName): \Illuminate\Database\Eloquent\Collection
    {
        $event = $this->eventRepository->findByNameInsensitive($eventName);
        
        if (!$event) {
            return collect();
        }

        return $this->formRepository->getActiveFormsForCity($event->id);
    }

    /**
     * Get all active forms for a city (alias for backward compatibility).
     */
    public function getActiveFormsForCity(string $cityName): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getActiveFormsForEvent($cityName);
    }

    /**
     * Get the first active form for a city (for backward compatibility).
     */
    public function getActiveFormForCity(string $cityName): ?Form
    {
        $forms = $this->getActiveFormsForCity($cityName);
        $form = $forms->first();
        
        if ($form) {
            // Load the necessary relationships
            $form->load(['formFieldOrders.fieldJson', 'formFieldOrders.formCategory.formOptions']);
        }
        
        return $form;
    }

    /**
     * Get a form by its slug.
     */
    public function getFormBySlug(string $slug): ?Form
    {
        return Form::bySlug($slug)
            ->active()
            ->with(['formFieldOrders.fieldJson', 'formFieldOrders.formCategory.formOptions'])
            ->first();
    }

    /**
     * Create a new form.
     */
    public function createForm(array $data): Form
    {
        return DB::transaction(function () use ($data) {
            // Increment version for the city
            $latestVersion = Form::where('event_id', $data['event_id'])
                ->max('version') ?? 0;
            $data['version'] = $latestVersion + 1;

            return Form::create($data);
        });
    }

    /**
     * Create a new form with relational structure.
     */
    public function createFormWithRelationalData(array $formData, array $fieldsData): Form
    {
        return DB::transaction(function () use ($formData, $fieldsData) {
            // Create the form
            $latestVersion = Form::where('event_id', $formData['event_id'])
                ->max('version') ?? 0;
            $formData['version'] = $latestVersion + 1;

            $form = Form::create($formData);

            // Process fields - each field gets its own category
            $this->processFormFields($form, $fieldsData);

            return $form;
        });
    }

    /**
     * Update a form with relational structure.
     */
    public function updateFormWithRelationalData(Form $form, array $formData, array $fieldsData): Form
    {
        return DB::transaction(function () use ($form, $formData, $fieldsData) {
            // Update form data
            $form->update($formData);

            // Clear existing field orders
            $form->formFieldOrders()->delete();

            // Process new fields - each field gets its own category
            $this->processFormFields($form, $fieldsData);

            return $form->fresh();
        });
    }

    /**
     * Process form fields and create relational data.
     */
    private function processFormFields(Form $form, array $fieldsData): void
    {
        $fieldOrder = 1;

        foreach ($fieldsData as $fieldData) {
            // Create or get the field in fields_json table
            $fieldJson = FieldJson::updateOrCreate(
                ['key' => $fieldData['key']],
                [
                    'key' => $fieldData['key'],
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'required' => $fieldData['required'] ?? false,
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'validations' => $fieldData['validations'] ?? [],
                    'visible' => $fieldData['visible'] ?? null,
                    'default_value' => $fieldData['default_value'] ?? null,
                    'description' => $fieldData['description'] ?? null,
                    'is_active' => true,
                ]
            );

            // Create a category for this specific field
            $fieldCategory = FormCategory::updateOrCreate(
                ['code' => $fieldData['key']],
                [
                    'name' => $fieldData['label'],
                    'description' => 'Categoría para el campo: ' . $fieldData['label'],
                    'is_active' => true,
                ]
            );

            // Create form field order entry
            FormFieldOrder::create([
                'form_id' => $form->id,
                'form_category_id' => $fieldCategory->id,
                'field_json_id' => $fieldJson->id,
                'order' => $fieldData['order'] ?? $fieldOrder++,
                'extra_data' => [
                    'original_field_data' => $fieldData
                ],
            ]);

            // Handle select/checkbox options
            if (isset($fieldData['options']) && is_array($fieldData['options'])) {
                $this->processFieldOptions($fieldData, $fieldCategory);
            }
        }
    }

    /**
     * Process field options and create relational data.
     */
    private function processFieldOptions(array $fieldData, FormCategory $category): void
    {
        // Clear existing options for this category
        $category->formOptions()->delete();

        $optionOrder = 1;

        foreach ($fieldData['options'] as $option) {
            $value = is_array($option) ? $option['value'] : $option;
            $label = is_array($option) ? ($option['label'] ?? $option['value']) : $option;

            FormOption::create([
                'category_id' => $category->id,
                'value' => $value,
                'label' => $label,
                'order' => $optionOrder++,
                'description' => is_array($option) ? ($option['description'] ?? null) : null,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Update an existing form.
     */
    public function updateForm(Form $form, array $data): Form
    {
        return DB::transaction(function () use ($form, $data) {
            // If schema_json is being updated, create a new version
            if (isset($data['schema_json']) && $data['schema_json'] !== $form->schema_json) {
                $data['version'] = $form->version + 1;
            }

            $form->update($data);
            return $form->fresh();
        });
    }

    /**
     * Activate a form (allow multiple active forms per city).
     */
    public function activateForm(Form $form): Form
    {
        $form->update(['is_active' => true]);
        return $form->fresh();
    }

    /**
     * Deactivate a form.
     */
    public function deactivateForm(Form $form): Form
    {
        $form->update(['is_active' => false]);
        return $form->fresh();
    }

    /**
     * Generate validation rules from form schema.
     */
    public function generateValidationRules(Form $form, array $formData = []): array
    {
        return $form->getValidationRules($formData);
    }

    /**
     * Generate validation rules from relational form structure.
     */
    public function generateValidationRulesFromRelational(Form $form, array $formData = []): array
    {
        $rules = [];
        $fields = $form->getRelationalFields();

        foreach ($fields as $field) {
            $fieldRules = [];

            // Check if field is conditionally visible
            $isVisible = $this->isFieldVisibleRelational($field, $formData);

            // Campo requerido (solo si es visible)
            if ($field['required'] && $isVisible) {
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
     * Check if a field should be visible based on conditional logic (relational version).
     */
    public function isFieldVisibleRelational(array $field, array $formData = []): bool
    {
        // If no visibility condition is set, field is always visible
        if (!$field['visible']) {
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

    /**
     * Validate form submission data.
     */
    public function validateSubmissionData(Form $form, array $data): array
    {
        // Try to use relational validation first, fallback to legacy JSON validation
        try {
            $rules = $this->generateValidationRulesFromRelational($form, $data);
        } catch (\Exception $e) {
            // Fallback to legacy JSON validation
            $rules = $this->generateValidationRules($form, $data);
        }
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Submit a form.
     * $data should contain only dynamic fields (already separated in controller)
     */
    public function submitForm(Form $form, Participant $participant, array $data, $user = null): FormSubmission
    {
        return DB::transaction(function () use ($form, $participant, $data, $user) {
            // Create the submission with dynamic fields in data_json
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'participant_id' => $participant->id,
                'data_json' => $data, // Only dynamic fields (already separated)
                'submitted_at' => now(),
            ]);

            \Log::info('Form submission created', [
                'submission_id' => $submission->id,
                'form_id' => $form->id,
                'participant_id' => $participant->id,
                'dynamic_fields_count' => count($data),
                'dynamic_fields' => array_keys($data)
            ]);

            return $submission;
        });
    }

    /**
     * Get form submissions with filters.
     */
    public function getSubmissions(array $filters = []): Collection
    {
        $query = FormSubmission::with(['form', 'participant', 'form.event']);

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
            });
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        if (isset($filters['participant_id'])) {
            $query->where('participant_id', $filters['participant_id']);
        }

        return $query->orderBy('submitted_at', 'desc')->get();
    }

    /**
     * Get form statistics.
     */
    public function getFormStatistics(Form $form): array
    {
        $totalSubmissions = $form->formSubmissions()->count();
        $uniqueParticipants = $form->formSubmissions()
            ->distinct('participant_id')
            ->count('participant_id');

        $submissionsByDate = $form->formSubmissions()
            ->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_submissions' => $totalSubmissions,
            'unique_participants' => $uniqueParticipants,
            'submissions_by_date' => $submissionsByDate,
        ];
    }

    /**
     * Check if a participant has already submitted a form.
     */
    public function hasParticipantSubmitted(Form $form, Participant $participant): bool
    {
        return $form->formSubmissions()
            ->where('participant_id', $participant->id)
            ->exists();
    }

    /**
     * Get the latest submission for a participant and form.
     */
    public function getLatestParticipantSubmission(Form $form, Participant $participant): ?FormSubmission
    {
        return $form->formSubmissions()
            ->where('participant_id', $participant->id)
            ->latest('submitted_at')
            ->first();
    }
}
