<?php

namespace App\Http\Requests\Form;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'schema_json' => ['required'],
            'style_json' => ['nullable'],
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
            'event_id.required' => 'El evento es obligatorio.',
            'event_id.exists' => 'El evento seleccionado no existe.',
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
            } else {
                // Store the JSON error for later validation
                $this->merge([
                    'schema_json_error' => json_last_error_msg()
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
            } else {
                // Store the JSON error for later validation
                $this->merge([
                    'style_json_error' => json_last_error_msg()
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
            // Check for JSON parsing errors first
            if ($this->has('schema_json_error')) {
                $validator->errors()->add('schema_json', 'Error en el formato JSON: ' . $this->schema_json_error);
                return; // Don't continue with other validations if JSON is invalid
            }
            
            if ($this->has('style_json_error')) {
                $validator->errors()->add('style_json', 'Error en el formato JSON de estilos: ' . $this->style_json_error);
            }
            
            // Basic JSON validation - just check if it's valid JSON and has fields
            $schemaJson = $this->schema_json;
            if (is_string($schemaJson)) {
                $schemaArray = json_decode($schemaJson, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Basic validation: check if it has fields array
                    if (!isset($schemaArray['fields']) || !is_array($schemaArray['fields'])) {
                        $validator->errors()->add('schema_json', 'El JSON debe contener un array de campos llamado "fields".');
                    }
                } else {
                    $validator->errors()->add('schema_json', 'Error en el formato JSON: ' . json_last_error_msg());
                }
            }
            
            // Basic style JSON validation
            $styleJson = $this->style_json;
            if (is_string($styleJson) && !empty($styleJson)) {
                $styleArray = json_decode($styleJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validator->errors()->add('style_json', 'Error en el formato JSON de estilos: ' . json_last_error_msg());
                }
            }
        });
    }
}