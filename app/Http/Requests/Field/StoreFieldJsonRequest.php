<?php

namespace App\Http\Requests\Field;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldJsonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'field_json' => ['required', 'array'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'field_json.required' => 'La configuración del campo es obligatoria.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert JSON string to array for validation
        if ($this->has('field_json') && is_string($this->field_json)) {
            $fieldArray = json_decode($this->field_json, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge([
                    'field_json' => $fieldArray
                ]);
            }
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate field structure
            if ($this->has('field_json') && is_array($this->field_json)) {
                $this->validateFieldStructure($validator);
            }
        });
    }

    /**
     * Validate the field structure.
     */
    private function validateFieldStructure($validator): void
    {
        $field = $this->field_json;

        // Check required fields
        if (!isset($field['key']) || empty($field['key'])) {
            $validator->errors()->add('field_json.key', 'El campo debe tener una clave (key).');
        } else {
            // Check if key is unique
            $existingField = \App\Models\FieldJson::where('key', $field['key'])->first();
            if ($existingField) {
                $validator->errors()->add('field_json.key', 'Ya existe un campo con esta clave.');
            }
        }

        if (!isset($field['label']) || empty($field['label'])) {
            $validator->errors()->add('field_json.label', 'El campo debe tener una etiqueta (label).');
        }

        if (!isset($field['type']) || empty($field['type'])) {
            $validator->errors()->add('field_json.type', 'El campo debe tener un tipo (type).');
        } else {
            $validTypes = ['text', 'email', 'number', 'textarea', 'select', 'checkbox', 'date'];
            if (!in_array($field['type'], $validTypes)) {
                $validator->errors()->add('field_json.type', 'El tipo de campo debe ser uno de: ' . implode(', ', $validTypes));
            }
        }

        // Validate select options
        if (isset($field['type']) && $field['type'] === 'select') {
            if (!isset($field['options']) || !is_array($field['options']) || empty($field['options'])) {
                $validator->errors()->add('field_json.options', 'Los campos de tipo select deben tener opciones.');
            }
        }

        // Validate field validations if they exist
        if (isset($field['validations'])) {
            $this->validateFieldValidations($field['validations'], 'field_json.validations', $validator);
        }

        // Validate conditional visibility if it exists
        if (isset($field['visible'])) {
            $this->validateConditionalVisibility($field['visible'], 'field_json.visible', $validator);
        }
    }

    protected function validateFieldValidations(array $validations, string $fieldPrefix, $validator): void
    {
        // Validate dates
        if (isset($validations['min_date']) && !$this->isValidDate($validations['min_date'])) {
            $validator->errors()->add($fieldPrefix . '.min_date', 'La fecha mínima debe tener formato YYYY-MM-DD.');
        }

        if (isset($validations['max_date']) && !$this->isValidDate($validations['max_date'])) {
            $validator->errors()->add($fieldPrefix . '.max_date', 'La fecha máxima debe tener formato YYYY-MM-DD.');
        }

        if (isset($validations['date_range'])) {
            $range = $validations['date_range'];
            if (isset($range['start']) && !$this->isValidDate($range['start'])) {
                $validator->errors()->add($fieldPrefix . '.date_range.start', 'La fecha de inicio debe tener formato YYYY-MM-DD.');
            }
            if (isset($range['end']) && !$this->isValidDate($range['end'])) {
                $validator->errors()->add($fieldPrefix . '.date_range.end', 'La fecha de fin debe tener formato YYYY-MM-DD.');
            }
        }

        // Validate ages
        if (isset($validations['min_age']) && (!is_numeric($validations['min_age']) || $validations['min_age'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.min_age', 'La edad mínima debe ser un número positivo.');
        }

        if (isset($validations['max_age']) && (!is_numeric($validations['max_age']) || $validations['max_age'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.max_age', 'La edad máxima debe ser un número positivo.');
        }

        // Validate lengths
        if (isset($validations['min_length']) && (!is_numeric($validations['min_length']) || $validations['min_length'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.min_length', 'La longitud mínima debe ser un número positivo.');
        }

        if (isset($validations['max_length']) && (!is_numeric($validations['max_length']) || $validations['max_length'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.max_length', 'La longitud máxima debe ser un número positivo.');
        }

        // Validate numeric values
        if (isset($validations['min_value']) && !is_numeric($validations['min_value'])) {
            $validator->errors()->add($fieldPrefix . '.min_value', 'El valor mínimo debe ser un número.');
        }

        if (isset($validations['max_value']) && !is_numeric($validations['max_value'])) {
            $validator->errors()->add($fieldPrefix . '.max_value', 'El valor máximo debe ser un número.');
        }

        // Validate selections
        if (isset($validations['min_selections']) && (!is_numeric($validations['min_selections']) || $validations['min_selections'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.min_selections', 'El número mínimo de selecciones debe ser un número positivo.');
        }

        if (isset($validations['max_selections']) && (!is_numeric($validations['max_selections']) || $validations['max_selections'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.max_selections', 'El número máximo de selecciones debe ser un número positivo.');
        }

        // Validate max elements
        if (isset($validations['max_elements']) && (!is_numeric($validations['max_elements']) || $validations['max_elements'] < 1)) {
            $validator->errors()->add($fieldPrefix . '.max_elements', 'El número máximo de elementos debe ser un número positivo mayor a 0.');
        }

        // Validate format patterns
        if (isset($validations['pattern']) && !$this->isValidRegex($validations['pattern'])) {
            $validator->errors()->add($fieldPrefix . '.pattern', 'El patrón de validación no es una expresión regular válida.');
        }

        // Validate custom formats
        if (isset($validations['format'])) {
            $validFormats = ['dni', 'phone', 'email', 'url', 'postal_code', 'currency', 'percentage'];
            if (!in_array($validations['format'], $validFormats)) {
                $validator->errors()->add($fieldPrefix . '.format', 'El formato debe ser uno de: ' . implode(', ', $validFormats));
            }
        }

        // Validate file uploads
        if (isset($validations['file_types'])) {
            if (!is_array($validations['file_types'])) {
                $validator->errors()->add($fieldPrefix . '.file_types', 'Los tipos de archivo deben ser un array.');
            }
        }

        if (isset($validations['max_file_size']) && (!is_numeric($validations['max_file_size']) || $validations['max_file_size'] <= 0)) {
            $validator->errors()->add($fieldPrefix . '.max_file_size', 'El tamaño máximo de archivo debe ser un número positivo.');
        }

        // Validate conditional requirements
        if (isset($validations['required_if'])) {
            if (!is_array($validations['required_if']) || !isset($validations['required_if']['field']) || !isset($validations['required_if']['value'])) {
                $validator->errors()->add($fieldPrefix . '.required_if', 'La validación required_if debe tener los campos "field" y "value".');
            }
        }

        // Validate uniqueness constraints
        if (isset($validations['unique']) && !is_bool($validations['unique'])) {
            $validator->errors()->add($fieldPrefix . '.unique', 'La validación unique debe ser verdadero o falso.');
        }

        // Validate character restrictions
        if (isset($validations['allowed_chars']) && !is_string($validations['allowed_chars'])) {
            $validator->errors()->add($fieldPrefix . '.allowed_chars', 'Los caracteres permitidos deben ser una cadena.');
        }

        if (isset($validations['forbidden_chars']) && !is_string($validations['forbidden_chars'])) {
            $validator->errors()->add($fieldPrefix . '.forbidden_chars', 'Los caracteres prohibidos deben ser una cadena.');
        }

        // Validate word count
        if (isset($validations['min_words']) && (!is_numeric($validations['min_words']) || $validations['min_words'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.min_words', 'El número mínimo de palabras debe ser un número positivo.');
        }

        if (isset($validations['max_words']) && (!is_numeric($validations['max_words']) || $validations['max_words'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.max_words', 'El número máximo de palabras debe ser un número positivo.');
        }

        // Validate decimal places
        if (isset($validations['decimal_places']) && (!is_numeric($validations['decimal_places']) || $validations['decimal_places'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.decimal_places', 'El número de decimales debe ser un número positivo.');
        }

        // Validate step for numeric inputs
        if (isset($validations['step']) && !is_numeric($validations['step'])) {
            $validator->errors()->add($fieldPrefix . '.step', 'El paso debe ser un número.');
        }
    }

    protected function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    protected function isValidRegex(string $pattern): bool
    {
        return @preg_match($pattern, '') !== false;
    }

    /**
     * Validate conditional visibility configuration.
     */
    protected function validateConditionalVisibility(array $visible, string $fieldPrefix, $validator): void
    {
        // Validate required fields for conditional visibility
        if (!isset($visible['model']) || empty($visible['model'])) {
            $validator->errors()->add($fieldPrefix . '.model', 'El campo visible debe tener un modelo (model) de referencia.');
        }

        if (!isset($visible['value'])) {
            $validator->errors()->add($fieldPrefix . '.value', 'El campo visible debe tener un valor (value) de comparación.');
        }

        if (!isset($visible['condition']) || empty($visible['condition'])) {
            $validator->errors()->add($fieldPrefix . '.condition', 'El campo visible debe tener una condición (condition).');
        } else {
            $validConditions = ['equal', 'not_equal', 'contains', 'not_contains', 'greater_than', 'less_than', 'greater_equal', 'less_equal'];
            if (!in_array($visible['condition'], $validConditions)) {
                $validator->errors()->add($fieldPrefix . '.condition', 'La condición debe ser una de: ' . implode(', ', $validConditions));
            }
        }
    }
}
