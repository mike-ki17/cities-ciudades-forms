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

        // Fixed participant fields validation rules
        $participantRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'document_type' => ['required', 'string', 'max:50'], // Allow any document type up to 50 characters
            'document_number' => ['required', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date', 'before:today'],
        ];

        // Get dynamic form fields validation rules
        $dynamicRules = $form->getValidationRules($this->all());

        // Merge participant rules with dynamic rules
        return array_merge($participantRules, $dynamicRules);
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
            
            // Validate document number uniqueness
            $this->validateDocumentNumberUniqueness($validator);
            
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
     * Validate document number uniqueness.
     * Note: We don't prevent duplicate document numbers, but we ensure they are handled properly
     * in the submission logic by associating with existing participants.
     */
    protected function validateDocumentNumberUniqueness($validator): void
    {
        $documentType = $this->input('document_type');
        $documentNumber = $this->input('document_number');
        
        if (!$documentType || !$documentNumber) {
            return;
        }

        // Check if document number already exists
        $existingParticipant = \App\Models\Participant::byDocument($documentType, $documentNumber)->first();
        
        if ($existingParticipant) {
            // Document number exists - this is allowed, but we can add a warning if needed
            // For now, we'll just log it for debugging
            \Log::info('Document number already exists, will associate with existing participant', [
                'document_type' => $documentType,
                'document_number' => $documentNumber,
                'participant_id' => $existingParticipant->id
            ]);
        }
    }

    /**
     * Validate that the user hasn't already submitted this form.
     * For public forms, we allow multiple submissions from the same participant.
     */
    protected function validateDuplicateSubmission($validator, Form $form): void
    {
        // For public access, we allow multiple submissions
        // This can be customized based on business requirements
        // For example, you could check by email or document number if needed
    }

}
