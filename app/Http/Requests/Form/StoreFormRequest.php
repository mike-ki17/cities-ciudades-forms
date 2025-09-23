<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'schema_json' => ['required', 'array'],
            'style_json' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'version' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'event_id.required' => 'Debe seleccionar una ciudad.',
            'event_id.exists' => 'La ciudad seleccionada no existe.',
            'name.required' => 'El nombre del formulario es obligatorio.',
            'name.max' => 'El nombre del formulario no puede tener más de 255 caracteres.',
            'schema_json.required' => 'La configuración del formulario es obligatoria.',
            'version.min' => 'La versión debe ser al menos 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert JSON string to array for validation
        if ($this->has('schema_json') && is_string($this->schema_json)) {
            $schemaArray = json_decode($this->schema_json, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge([
                    'schema_json' => $schemaArray
                ]);
            }
        }

        // Convert style_json string to array for validation
        if ($this->has('style_json') && is_string($this->style_json)) {
            $styleArray = json_decode($this->style_json, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge([
                    'style_json' => $styleArray
                ]);
            }
        }

        // Convert form_shadow string values to boolean
        if ($this->has('style_json') && is_array($this->style_json) && isset($this->style_json['form_shadow'])) {
            $formShadow = $this->style_json['form_shadow'];
            if (in_array($formShadow, ['0', '1', 0, 1], true)) {
                $styleJson = $this->style_json;
                $styleJson['form_shadow'] = (bool) $formShadow;
                $this->merge(['style_json' => $styleJson]);
            }
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate JSON structure
            if ($this->has('schema_json') && is_array($this->schema_json)) {
                $this->validateSchemaStructure($validator);
                $this->validateDocumentSpaces($validator);
            }
            
            // Validate style structure
            if ($this->has('style_json') && is_array($this->style_json)) {
                $this->validateStyleStructure($validator);
            }
        });
    }

    /**
     * Validate the schema structure.
     */
    private function validateSchemaStructure($validator): void
    {
        $schema = $this->schema_json;

        // Check if schema has fields
        if (!isset($schema['fields']) || !is_array($schema['fields'])) {
            $validator->errors()->add('schema_json', 'El esquema debe contener un array de campos.');
            return;
        }

        // Validate each field
        foreach ($schema['fields'] as $index => $field) {
            $fieldPrefix = "schema_json.fields.{$index}";

            if (!isset($field['key']) || empty($field['key'])) {
                $validator->errors()->add($fieldPrefix . '.key', 'Cada campo debe tener una clave (key).');
            }

            if (!isset($field['label']) || empty($field['label'])) {
                $validator->errors()->add($fieldPrefix . '.label', 'Cada campo debe tener una etiqueta (label).');
            }

            if (!isset($field['type']) || empty($field['type'])) {
                $validator->errors()->add($fieldPrefix . '.type', 'Cada campo debe tener un tipo (type).');
            } else {
                $validTypes = ['text', 'email', 'number', 'textarea', 'select', 'checkbox', 'date'];
                if (!in_array($field['type'], $validTypes)) {
                    $validator->errors()->add($fieldPrefix . '.type', 'El tipo de campo debe ser uno de: ' . implode(', ', $validTypes));
                }
            }

            // Validate select options
            if (isset($field['type']) && $field['type'] === 'select') {
                if (!isset($field['options']) || !is_array($field['options']) || empty($field['options'])) {
                    $validator->errors()->add($fieldPrefix . '.options', 'Los campos de tipo select deben tener opciones.');
                }
            }

            // Validate field validations if they exist
            if (isset($field['validations'])) {
                $this->validateFieldValidations($field['validations'], $fieldPrefix, $validator);
            }

            // Validate conditional visibility if it exists
            if (isset($field['visible'])) {
                $this->validateConditionalVisibility($field['visible'], $fieldPrefix, $validator);
            }
        }
    }

    protected function validateFieldValidations(array $validations, string $fieldPrefix, $validator): void
    {
        // Validate dates
        if (isset($validations['min_date']) && !$this->isValidDate($validations['min_date'])) {
            $validator->errors()->add($fieldPrefix . '.validations.min_date', 'La fecha mínima debe tener formato YYYY-MM-DD.');
        }

        if (isset($validations['max_date']) && !$this->isValidDate($validations['max_date'])) {
            $validator->errors()->add($fieldPrefix . '.validations.max_date', 'La fecha máxima debe tener formato YYYY-MM-DD.');
        }

        if (isset($validations['date_range'])) {
            $range = $validations['date_range'];
            if (isset($range['start']) && !$this->isValidDate($range['start'])) {
                $validator->errors()->add($fieldPrefix . '.validations.date_range.start', 'La fecha de inicio debe tener formato YYYY-MM-DD.');
            }
            if (isset($range['end']) && !$this->isValidDate($range['end'])) {
                $validator->errors()->add($fieldPrefix . '.validations.date_range.end', 'La fecha de fin debe tener formato YYYY-MM-DD.');
            }
        }

        // Validate ages
        if (isset($validations['min_age']) && (!is_numeric($validations['min_age']) || $validations['min_age'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.min_age', 'La edad mínima debe ser un número positivo.');
        }

        if (isset($validations['max_age']) && (!is_numeric($validations['max_age']) || $validations['max_age'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_age', 'La edad máxima debe ser un número positivo.');
        }

        // Validate lengths
        if (isset($validations['min_length']) && (!is_numeric($validations['min_length']) || $validations['min_length'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.min_length', 'La longitud mínima debe ser un número positivo.');
        }

        if (isset($validations['max_length']) && (!is_numeric($validations['max_length']) || $validations['max_length'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_length', 'La longitud máxima debe ser un número positivo.');
        }

        // Validate numeric values
        if (isset($validations['min_value']) && !is_numeric($validations['min_value'])) {
            $validator->errors()->add($fieldPrefix . '.validations.min_value', 'El valor mínimo debe ser un número.');
        }

        if (isset($validations['max_value']) && !is_numeric($validations['max_value'])) {
            $validator->errors()->add($fieldPrefix . '.validations.max_value', 'El valor máximo debe ser un número.');
        }

        // Validate selections
        if (isset($validations['min_selections']) && (!is_numeric($validations['min_selections']) || $validations['min_selections'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.min_selections', 'El número mínimo de selecciones debe ser un número positivo.');
        }

        if (isset($validations['max_selections']) && (!is_numeric($validations['max_selections']) || $validations['max_selections'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_selections', 'El número máximo de selecciones debe ser un número positivo.');
        }

        // Validate max elements
        if (isset($validations['max_elements']) && (!is_numeric($validations['max_elements']) || $validations['max_elements'] < 1)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_elements', 'El número máximo de elementos debe ser un número positivo mayor a 0.');
        }

        // Validate format patterns
        if (isset($validations['pattern']) && !$this->isValidRegex($validations['pattern'])) {
            $validator->errors()->add($fieldPrefix . '.validations.pattern', 'El patrón de validación no es una expresión regular válida.');
        }

        // Validate custom formats
        if (isset($validations['format'])) {
            $validFormats = ['dni', 'phone', 'email', 'url', 'postal_code', 'currency', 'percentage'];
            if (!in_array($validations['format'], $validFormats)) {
                $validator->errors()->add($fieldPrefix . '.validations.format', 'El formato debe ser uno de: ' . implode(', ', $validFormats));
            }
        }

        // Validate file uploads
        if (isset($validations['file_types'])) {
            if (!is_array($validations['file_types'])) {
                $validator->errors()->add($fieldPrefix . '.validations.file_types', 'Los tipos de archivo deben ser un array.');
            }
        }

        if (isset($validations['max_file_size']) && (!is_numeric($validations['max_file_size']) || $validations['max_file_size'] <= 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_file_size', 'El tamaño máximo de archivo debe ser un número positivo.');
        }

        // Validate conditional requirements
        if (isset($validations['required_if'])) {
            if (!is_array($validations['required_if']) || !isset($validations['required_if']['field']) || !isset($validations['required_if']['value'])) {
                $validator->errors()->add($fieldPrefix . '.validations.required_if', 'La validación required_if debe tener los campos "field" y "value".');
            }
        }

        // Validate uniqueness constraints
        if (isset($validations['unique']) && !is_bool($validations['unique'])) {
            $validator->errors()->add($fieldPrefix . '.validations.unique', 'La validación unique debe ser verdadero o falso.');
        }

        // Validate character restrictions
        if (isset($validations['allowed_chars']) && !is_string($validations['allowed_chars'])) {
            $validator->errors()->add($fieldPrefix . '.validations.allowed_chars', 'Los caracteres permitidos deben ser una cadena.');
        }

        if (isset($validations['forbidden_chars']) && !is_string($validations['forbidden_chars'])) {
            $validator->errors()->add($fieldPrefix . '.validations.forbidden_chars', 'Los caracteres prohibidos deben ser una cadena.');
        }

        // Validate word count
        if (isset($validations['min_words']) && (!is_numeric($validations['min_words']) || $validations['min_words'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.min_words', 'El número mínimo de palabras debe ser un número positivo.');
        }

        if (isset($validations['max_words']) && (!is_numeric($validations['max_words']) || $validations['max_words'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.max_words', 'El número máximo de palabras debe ser un número positivo.');
        }

        // Validate decimal places
        if (isset($validations['decimal_places']) && (!is_numeric($validations['decimal_places']) || $validations['decimal_places'] < 0)) {
            $validator->errors()->add($fieldPrefix . '.validations.decimal_places', 'El número de decimales debe ser un número positivo.');
        }

        // Validate step for numeric inputs
        if (isset($validations['step']) && !is_numeric($validations['step'])) {
            $validator->errors()->add($fieldPrefix . '.validations.step', 'El paso debe ser un número.');
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
            $validator->errors()->add($fieldPrefix . '.visible.model', 'El campo visible debe tener un modelo (model) de referencia.');
        }

        if (!isset($visible['value'])) {
            $validator->errors()->add($fieldPrefix . '.visible.value', 'El campo visible debe tener un valor (value) de comparación.');
        }

        if (!isset($visible['condition']) || empty($visible['condition'])) {
            $validator->errors()->add($fieldPrefix . '.visible.condition', 'El campo visible debe tener una condición (condition).');
        } else {
            $validConditions = ['equal', 'not_equal', 'contains', 'not_contains', 'greater_than', 'less_than', 'greater_equal', 'less_equal'];
            if (!in_array($visible['condition'], $validConditions)) {
                $validator->errors()->add($fieldPrefix . '.visible.condition', 'La condición debe ser una de: ' . implode(', ', $validConditions));
            }
        }
    }

    /**
     * Validate that document fields have maximum 12 spaces.
     */
    private function validateDocumentSpaces($validator): void
    {
        $schema = $this->schema_json;
        
        if (!isset($schema['fields']) || !is_array($schema['fields'])) {
            return;
        }

        foreach ($schema['fields'] as $index => $field) {
            $fieldPrefix = "schema_json.fields.{$index}";
            
            // Verificar si es un campo de documento (puedes ajustar esta condición según tu lógica)
            if (isset($field['type']) && $field['type'] === 'text' && 
                (isset($field['key']) && str_contains(strtolower($field['key']), 'document'))) {
                
                // Si el campo tiene un valor por defecto o ejemplo, validarlo
                if (isset($field['default_value']) && is_string($field['default_value'])) {
                    $spaceCount = substr_count($field['default_value'], ' ');
                    if ($spaceCount > 12) {
                        $validator->errors()->add(
                            $fieldPrefix . '.default_value', 
                            'El campo documento no puede tener más de 12 espacios.'
                        );
                    }
                }
                
                // Agregar validación automática de espacios en las validaciones del campo
                if (!isset($field['validations'])) {
                    $field['validations'] = [];
                }
                
                // Agregar validación personalizada para espacios
                $field['validations']['max_spaces'] = 12;
                
                // Actualizar el schema con la validación
                $this->schema_json['fields'][$index] = $field;
            }
        }
    }

    /**
     * Validate the style structure.
     */
    private function validateStyleStructure($validator): void
    {
        $style = $this->style_json;

        // Validate header images
        if (isset($style['header_image_1']) && !empty($style['header_image_1'])) {
            if (!filter_var($style['header_image_1'], FILTER_VALIDATE_URL)) {
                $validator->errors()->add('style_json.header_image_1', 'La URL de la imagen principal del header no es válida.');
            }
        }

        if (isset($style['header_image_2']) && !empty($style['header_image_2'])) {
            if (!filter_var($style['header_image_2'], FILTER_VALIDATE_URL)) {
                $validator->errors()->add('style_json.header_image_2', 'La URL de la imagen secundaria del header no es válida.');
            }
        }

        // Validate background color
        if (isset($style['background_color']) && !empty($style['background_color'])) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $style['background_color'])) {
                $validator->errors()->add('style_json.background_color', 'El color de fondo debe ser un código hexadecimal válido (ej: #ffffff).');
            }
        }

        // Validate background texture
        if (isset($style['background_texture']) && !empty($style['background_texture'])) {
            if (!filter_var($style['background_texture'], FILTER_VALIDATE_URL)) {
                $validator->errors()->add('style_json.background_texture', 'La URL de la textura de fondo no es válida.');
            }
        }

        // Validate primary color
        if (isset($style['primary_color']) && !empty($style['primary_color'])) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $style['primary_color'])) {
                $validator->errors()->add('style_json.primary_color', 'El color principal debe ser un código hexadecimal válido (ej: #00ffbd).');
            }
        }

        // Validate border radius
        if (isset($style['border_radius']) && !empty($style['border_radius'])) {
            $validRadius = ['0px', '4px', '8px', '12px', '16px', '24px'];
            if (!in_array($style['border_radius'], $validRadius)) {
                $validator->errors()->add('style_json.border_radius', 'El border radius debe ser uno de: ' . implode(', ', $validRadius));
            }
        }

        // Validate form shadow (should be boolean or string "0"/"1")
        if (isset($style['form_shadow'])) {
            $formShadow = $style['form_shadow'];
            if (!is_bool($formShadow) && !in_array($formShadow, ['0', '1', 0, 1], true)) {
                $validator->errors()->add('style_json.form_shadow', 'El valor de sombra del formulario debe ser verdadero o falso.');
            }
        }
    }
}
