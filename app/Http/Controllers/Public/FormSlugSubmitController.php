<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\SubmitFormSlugRequest;
use App\Mail\ParticipationNotificationMail;
use App\Services\FormService;
use App\Services\ParticipantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
            'first_name' => trim($allData['first_name'] ?? ''),
            'last_name' => trim($allData['last_name'] ?? ''),
            'name' => trim(($allData['first_name'] ?? '') . ' ' . ($allData['last_name'] ?? '')), // Concatenate for backward compatibility
            'email' => trim($allData['email'] ?? ''),
            'phone' => trim($allData['phone'] ?? $allData['telefono'] ?? ''),
            'document_type' => $allData['document_type'] ?? 'DNI',
            'document_number' => strtoupper(str_replace(' ', '', trim($allData['document_number'] ?? ''))),
            'birth_date' => $allData['birth_date'] ?? null,
            'representative_name' => trim($allData['representative_name'] ?? ''),
            'representative_document_type' => $allData['representative_document_type'] ?? null,
            'representative_document_number' => $allData['representative_document_number'] ? strtoupper(str_replace(' ', '', trim($allData['representative_document_number']))) : null,
            'representative_address' => trim($allData['representative_address'] ?? ''),
            'representative_phone' => trim($allData['representative_phone'] ?? ''),
            'representative_email' => trim($allData['representative_email'] ?? ''),
            'representative_authorization' => isset($allData['representative_authorization']) ? (bool)$allData['representative_authorization'] : false,
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
            'first_name', 'last_name', 'name', 'nombre',  // Name fields
            'email',                    // Email field
            'phone', 'telefono',        // Phone fields
            'document_type',            // Document type
            'document_number',          // Document number
            'birth_date', 'fecha_nacimiento',  // Birth date fields
            'representative_name',              // Representative name field
            'representative_document_type',     // Representative document type
            'representative_document_number',   // Representative document number
            'representative_address',           // Representative address
            'representative_phone',             // Representative phone
            'representative_email',             // Representative email
            'representative_authorization'      // Representative authorization
        ];
        
        foreach ($allData as $key => $value) {
            // Fixed participant fields always go to participants table, never to form_submissions
            // Only include fields that are NOT fixed participant fields
            if (!in_array($key, $fixedParticipantFields)) {
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

            // Send email notification to participant
            try {
                Mail::to($participant->email)->send(new ParticipationNotificationMail($form, $participant, $submission));
                \Log::info('Email notification sent successfully', [
                    'participant_email' => $participant->email,
                    'form_id' => $form->id,
                    'submission_id' => $submission->id
                ]);
            } catch (\Exception $emailException) {
                // Log email error but don't fail the form submission
                \Log::error('Failed to send email notification', [
                    'participant_email' => $participant->email,
                    'form_id' => $form->id,
                    'submission_id' => $submission->id,
                    'error' => $emailException->getMessage()
                ]);
            }

            // Redirect to success page after successful submission
            return redirect()->route('public.forms.slug.show', ['slug' => $slug]);
                
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