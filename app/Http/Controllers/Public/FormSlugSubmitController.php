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

        $user = Auth::user();
        
        // Check if user has already submitted this form
        if ($user->hasSubmittedForm($form->id)) {
            return redirect()->route('public.forms.slug.show', ['slug' => $slug])
                ->with('error', 'Ya has completado este formulario. No puedes volver a llenarlo.');
        }

        // Get participant
        $participant = $user->participant;
        
        if (!$participant) {
            // Create participant from form data if user doesn't have one
            $participantData = [
                'name' => $request->input('name', $user->name),
                'email' => $request->input('email', $user->email),
                'phone' => $request->input('phone', null),
                'document_type' => 'DNI',
                'document_number' => $request->input('document_number', '00000000'),
                'city_id' => $form->city_id,
            ];
            
            $participant = $this->participantService->createOrGetParticipant($participantData);
            
            // Link participant to user
            $user->update(['participant_id' => $participant->id]);
        }

        try {
            // Submit the form with validated data
            $submission = $this->formService->submitForm($form, $participant, $request->validated(), $user);

            return redirect()->route('public.forms.slug.show', ['slug' => $slug])
                ->with('success', 'Formulario enviado exitosamente.');
                
        } catch (ValidationException $e) {
            \Log::info('Validation exception caught:', [
                'errors' => $e->errors(),
                'form_slug' => $slug,
                'user_id' => $user->id
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al enviar formulario: ' . $e->getMessage(), [
                'form_slug' => $slug,
                'user_id' => $user->id,
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al enviar el formulario: ' . $e->getMessage())
                ->withInput();
        }
    }
}