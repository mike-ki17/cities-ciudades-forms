<?php

namespace App\Http\Requests\Form;

use App\Models\Form;
use Illuminate\Foundation\Http\FormRequest;

class SubmitFormSlugRequest extends FormRequest
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
        $slug = $this->route('slug');
        
        if (!$slug) {
            return null;
        }

        // Get the form by slug
        $formService = app(\App\Services\FormService::class);
        return $formService->getFormBySlug($slug);
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
            
            // Validate duplicate submissions
            $this->validateDuplicateSubmission($validator, $form);
            
            // Log validation errors for debugging
            if ($validator->errors()->any()) {
                \Log::info('Validation errors found:', [
                    'errors' => $validator->errors()->all(),
                    'form_slug' => $this->route('slug'),
                    'form_data' => $this->all()
                ]);
            }
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

    /**
     * Validate that the user hasn't already submitted this form.
     */
    protected function validateDuplicateSubmission($validator, Form $form): void
    {
        $user = $this->user();
        
        if ($user && $user->hasSubmittedForm($form->id)) {
            $validator->errors()->add(
                'duplicate_submission', 
                'Ya has completado este formulario. No puedes volver a llenarlo.'
            );
        }
    }

}
