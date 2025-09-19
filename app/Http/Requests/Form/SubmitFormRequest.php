<?php

namespace App\Http\Requests\Form;

use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SubmitFormRequest extends FormRequest
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
        $form = $this->getForm();
        
        if (!$form) {
            return [];
        }

        // Get validation rules considering conditional fields
        return $form->getValidationRules($this->all());
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe ser una dirección de correo válida.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'regex' => 'El formato del campo :attribute no es válido.',
            'in' => 'El valor seleccionado para :attribute no es válido.',
        ];
    }

    /**
     * Get the form instance.
     */
    protected function getForm(): ?Form
    {
        $city = $this->route('city');
        
        if (!$city) {
            return null;
        }

        // Get the active form for the city
        $formService = app(\App\Services\FormService::class);
        return $formService->getActiveFormForCity($city);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $form = $this->getForm();
            
            if (!$form) {
                $validator->errors()->add('form', 'No se encontró un formulario válido.');
                return;
            }

            // Validate conditional fields
            $this->validateConditionalFields($validator, $form);
        });
    }

    /**
     * Validate conditional fields based on form data.
     */
    protected function validateConditionalFields($validator, Form $form): void
    {
        $fields = $form->getFieldsAttribute();
        $formData = $this->all();

        foreach ($fields as $field) {
            $fieldKey = $field['key'] ?? null;
            
            if (!$fieldKey) {
                continue;
            }

            // Check if field is conditionally visible
            $isVisible = $form->isFieldVisible($field, $formData);
            
            // If field is not visible but has a value, clear it
            if (!$isVisible && isset($formData[$fieldKey])) {
                $this->merge([$fieldKey => null]);
            }
        }
    }
}
