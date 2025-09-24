<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\SubmitFormSlugRequest;
use App\Services\FormService;
use App\Services\ParticipantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FormSlugSubmitController extends Controller
{
    public function __construct(
        private FormService $formService,
        private ParticipantService $participantService
    ) {}

    /**
     * Handle form submission by slug.
     */
    public function store(SubmitFormSlugRequest $request, string $slug): RedirectResponse
    {
        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form) {
            abort(404, 'Formulario no encontrado.');
        }

        // For public access, we don't require user authentication
        $user = null;

        // Separate data into two groups
        $allData = $request->validated();
        
        // Group 1: Fixed participant fields
        // Map form fields to participant fields (some form fields have different keys)
        $participantData = [
            'name' => $allData['name'] ?? $allData['nombre'] ?? null,
            'email' => $allData['email'] ?? null,
            'phone' => $allData['phone'] ?? $allData['telefono'] ?? null,
            'document_type' => $allData['document_type'] ?? 'DNI',
            'document_number' => $allData['document_number'] ?? null,
            'birth_date' => $allData['birth_date'] ?? null,
            'event_id' => $form->event_id,
        ];
        
        // Group 2: Dynamic fields (only those defined in form JSON, excluding fixed participant fields)
        $dynamicFields = [];
        
        // Try to get fields from relational structure first, fallback to legacy JSON
        try {
            $formFields = $form->getRelationalFields();
            if ($formFields->count() > 0) {
                $formFieldKeys = $formFields->pluck('key')->toArray();
            } else {
                // If relational fields is empty, use JSON fields
                $formFields = $form->getFieldsAttribute();
                $formFieldKeys = collect($formFields)->pluck('key')->toArray();
            }
        } catch (\Exception $e) {
            // Fallback to legacy JSON structure
            $formFields = $form->getFieldsAttribute();
            $formFieldKeys = collect($formFields)->pluck('key')->toArray();
        }
        
        // Fixed participant fields that should never be stored in form_submissions
        // These fields always go to participants table, never to form_submissions
        // Include both standard keys and form-specific keys
        $fixedParticipantFields = [
            'name', 'nombre',           // Name fields
            'email',                    // Email field
            'phone', 'telefono',        // Phone fields
            'document_type',            // Document type
            'document_number',          // Document number
            'birth_date', 'fecha_nacimiento'  // Birth date fields
        ];
        
        foreach ($allData as $key => $value) {
            // Only include fields that are in form JSON AND are not fixed participant fields
            // Fixed participant fields take priority and are never stored in form_submissions
            if (in_array($key, $formFieldKeys) && !in_array($key, $fixedParticipantFields)) {
                // Filter out empty values to avoid storing unnecessary data
                if (!empty($value) || $value === '0' || $value === 0) {
                    $dynamicFields[$key] = $value;
                }
            }
        }
        
        \Log::info('Form submission data separation', [
            'participant_data' => $participantData,
            'dynamic_fields' => $dynamicFields,
            'form_field_keys' => $formFieldKeys
        ]);
        
        // Create or get participant (will associate with existing if document_number exists)
        $participant = $this->participantService->createOrGetParticipant($participantData);

        // Check if participant has already submitted this form
        if ($this->formService->hasParticipantSubmitted($form, $participant)) {
            return redirect()->back()
                ->with('error', 'Ya has llenado este formulario anteriormente. No puedes enviarlo nuevamente.')
                ->withInput();
        }

        try {
            // Submit the form with only dynamic fields
            $submission = $this->formService->submitForm($form, $participant, $dynamicFields, $user);

            // Store participant ID in session for future form access
            $request->session()->put('participant_id', $participant->id);

            return redirect()->route('public.forms.slug.show', ['slug' => $slug])
                ->with('success', 'Formulario enviado exitosamente.');
                
        } catch (ValidationException $e) {
            \Log::info('Validation exception caught:', [
                'errors' => $e->errors(),
                'form_slug' => $slug,
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al enviar formulario: ' . $e->getMessage(), [
                'form_slug' => $slug,
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al enviar el formulario: ' . $e->getMessage())
                ->withInput();
        }
    }
}