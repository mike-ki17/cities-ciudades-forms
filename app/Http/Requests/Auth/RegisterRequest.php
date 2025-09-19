<?php

namespace App\Http\Requests\Auth;

use App\Models\City;
use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'unique:participant,email'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'document_type' => [
                'required',
                'string',
                Rule::in(['DNI', 'CE', 'PASSPORT', 'OTRO'])
            ],
            'document_number' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $documentType = $this->input('document_type');
                    $existingParticipant = Participant::where('document_type', $documentType)
                        ->where('document_number', $value)
                        ->exists();
                    
                    if ($existingParticipant) {
                        $fail('Ya existe un participante con este tipo y número de documento.');
                    }
                }
            ],
            'city_id' => [
                'required',
                'integer',
                'exists:city,id'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'document_type.required' => 'El campo tipo de documento es obligatorio.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El campo número de documento es obligatorio.',
            'document_number.max' => 'El número de documento no puede tener más de 50 caracteres.',
            'city_id.required' => 'El campo ciudad es obligatorio.',
            'city_id.exists' => 'La ciudad seleccionada no es válida.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'email',
            'password' => 'contraseña',
            'phone' => 'teléfono',
            'document_type' => 'tipo de documento',
            'document_number' => 'número de documento',
            'city_id' => 'ciudad',
        ];
    }
}