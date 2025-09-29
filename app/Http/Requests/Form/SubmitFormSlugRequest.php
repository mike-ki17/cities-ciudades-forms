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
            'first_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'last_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{7,12}$/'],
            'document_type' => ['required', 'string', 'in:CC,TI,CE,NIT,PASAPORTE,RC,PEP,PPT,OTRO'],
            'document_number' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) {
                $documentType = $this->input('document_type');
                if (!$this->validateDocumentNumber($documentType, $value)) {
                    $fail($this->getDocumentValidationMessage($documentType));
                }
            }],
            'birth_date' => ['required', 'date', 'before:today', 'before_or_equal:' . now()->subYears(16)->format('Y-m-d')],
            
            // Campos del representante legal (requeridos solo si es menor de edad)
            'representative_name' => ['nullable', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('El nombre del representante legal es obligatorio para menores de edad.');
                }
            }],
            'representative_document_type' => ['nullable', 'string', 'in:CC,TI,CE,NIT,PASAPORTE,RC,PEP,PPT,OTRO', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('El tipo de documento del representante legal es obligatorio para menores de edad.');
                }
            }],
            'representative_document_number' => ['nullable', 'string', 'max:50', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('El número de documento del representante legal es obligatorio para menores de edad.');
                }
                if (!empty($value)) {
                    $documentType = $this->input('representative_document_type');
                    if (!$this->validateDocumentNumber($documentType, $value)) {
                        $fail($this->getDocumentValidationMessage($documentType, 'representante legal'));
                    }
                }
            }],
            'representative_authorization' => ['nullable', function ($attribute, $value, $fail) {
                if ($this->isMinor() && (!$value || $value === '0' || $value === '')) {
                    $fail('La autorización del representante legal es obligatoria para menores de edad.');
                }
            }],
            'representative_address' => ['nullable', 'string', 'max:255', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('La dirección del representante legal es obligatoria para menores de edad.');
                }
            }],
            'representative_phone' => ['nullable', 'string', 'min:7', 'max:12', 'regex:/^[0-9]+$/', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('El teléfono del representante legal es obligatorio para menores de edad.');
                }
            }],
            'representative_email' => ['nullable', 'email', 'max:255', function ($attribute, $value, $fail) {
                if ($this->isMinor() && empty($value)) {
                    $fail('El correo electrónico del representante legal es obligatorio para menores de edad.');
                }
            }],
        ];

        // Get dynamic form fields validation rules
        // Try to use relational validation first, fallback to legacy JSON validation
        try {
            $formService = app(\App\Services\FormService::class);
            $dynamicRules = $formService->generateValidationRulesFromRelational($form, $this->all());
        } catch (\Exception $e) {
            // Fallback to legacy JSON validation
            $dynamicRules = $form->getValidationRules($this->all());
        }

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
            'before' => 'El campo :attribute debe ser una fecha anterior a hoy.',
            'before_or_equal' => 'El campo :attribute debe ser una fecha que indique que tienes al menos 16 años.',
            'first_name.min' => 'El nombre debe tener al menos 2 letras.',
            'first_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'last_name.min' => 'Los apellidos deben tener al menos 2 letras.',
            'last_name.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'phone.regex' => 'El teléfono debe contener entre 7 y 12 números.',
            'birth_date.before_or_equal' => 'Debes tener al menos 16 años para participar.',
            'representative_name.min' => 'El nombre del representante legal debe tener al menos 2 letras.',
            'representative_name.regex' => 'El nombre del representante legal solo puede contener letras y espacios.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'nombre',
            'last_name' => 'apellidos',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'document_type' => 'tipo de documento',
            'document_number' => 'número de documento',
            'birth_date' => 'fecha de nacimiento',
            'representative_name' => 'nombre del representante legal',
            'representative_document_type' => 'tipo de documento del representante legal',
            'representative_document_number' => 'número de documento del representante legal',
            'representative_authorization' => 'autorización del representante legal',
            'representative_address' => 'dirección del representante legal',
            'representative_phone' => 'teléfono del representante legal',
            'representative_email' => 'correo electrónico del representante legal',
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
        $formData = $this->all();

        // Try to use relational fields first, fallback to legacy JSON
        try {
            $fields = $form->getRelationalFields();
            $formService = app(\App\Services\FormService::class);
            
            foreach ($fields as $field) {
                $fieldKey = $field['key'] ?? null;
                
                if (!$fieldKey) {
                    continue;
                }

                // Check if field is conditionally visible using relational method
                $isVisible = $formService->isFieldVisibleRelational($field, $formData);
                
                // If field is not visible but has a value, clear it
                if (!$isVisible && isset($formData[$fieldKey])) {
                    $this->merge([$fieldKey => null]);
                }
            }
        } catch (\Exception $e) {
            // Fallback to legacy JSON validation
            $fields = $form->getFieldsAttribute();
            
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

    /**
     * Validate document number based on document type.
     */
    protected function validateDocumentNumber(string $documentType, string $documentNumber): bool
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
                // NUIP: hasta 15 dígitos numéricos
                // Carné Diplomático: 6-12 caracteres alfanuméricos
                return preg_match('/^[A-Z0-9]{6,15}$/', $cleanNumber);
                
            default:
                return false;
        }
    }

    /**
     * Check if the participant is a minor (under 18 years old).
     */
    protected function isMinor(): bool
    {
        $birthDate = $this->input('birth_date');
        
        if (!$birthDate) {
            return false;
        }
        
        $birthDate = \Carbon\Carbon::parse($birthDate);
        $age = $birthDate->age;
        
        return $age < 18;
    }

    /**
     * Get validation message for document type.
     */
    protected function getDocumentValidationMessage(string $documentType, string $context = 'participante'): string
    {
        $contextText = $context === 'familiar' ? ' del familiar' : '';
        
        switch ($documentType) {
            case 'CC':
                return 'La Cédula de Ciudadanía' . $contextText . ' debe contener solo números y tener entre 6 y 10 dígitos.';
            case 'TI':
                return 'La Tarjeta de Identidad' . $contextText . ' debe contener solo números y tener entre 6 y 11 dígitos.';
            case 'CE':
                return 'La Cédula de Extranjería' . $contextText . ' debe contener solo números y tener entre 6 y 15 dígitos.';
            case 'NIT':
                return 'El NIT' . $contextText . ' debe contener entre 9 y 15 dígitos y puede incluir un dígito de verificación separado por guión (ej: 900123456-7).';
            case 'PASAPORTE':
                return 'El Pasaporte' . $contextText . ' debe contener entre 6 y 12 caracteres alfanuméricos (letras y números).';
            case 'RC':
                return 'El Registro Civil' . $contextText . ' debe contener solo números y tener entre 10 y 15 dígitos.';
            case 'PEP':
                return 'El PEP' . $contextText . ' debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            case 'PPT':
                return 'El PPT' . $contextText . ' debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            case 'OTRO':
                return 'El documento' . $contextText . ' debe contener entre 6 y 15 caracteres alfanuméricos (letras y números).';
            default:
                return 'El formato del número de documento' . $contextText . ' no es válido.';
        }
    }

    /**
     * Prepare the data for validation.
     * Clean spaces from text fields.
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();
        
        // Clean spaces from text fields
        $textFields = ['first_name', 'last_name', 'email', 'phone', 'document_number', 'representative_name', 'representative_document_number', 'representative_address', 'representative_phone', 'representative_email'];
        
        foreach ($textFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = trim($data[$field]);
            }
        }
        
        // Clean document number specifically (remove all spaces and convert to uppercase)
        if (isset($data['document_number']) && is_string($data['document_number'])) {
            $data['document_number'] = strtoupper(str_replace(' ', '', $data['document_number']));
        }
        
        $this->merge($data);
    }

}
