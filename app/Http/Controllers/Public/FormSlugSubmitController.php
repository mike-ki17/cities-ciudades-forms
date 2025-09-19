<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\SubmitFormRequest;
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
    public function store(SubmitFormRequest $request, string $slug): RedirectResponse
    {
        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form) {
            abort(404, 'Formulario no encontrado.');
        }

        // Get participant
        $participant = null;
        
        if (Auth::check() && Auth::user()->participant) {
            $participant = Auth::user()->participant;
        } else {
            // For non-authenticated users, create an anonymous participant
            // We'll use email and name from the form data if available
            $participantData = [
                'name' => $request->input('name', 'Usuario Anónimo'),
                'email' => $request->input('email', 'anonymous@example.com'),
                'phone' => $request->input('phone', null),
                'city_id' => $form->city_id,
            ];
            
            $participant = $this->participantService->createParticipant($participantData);
        }

        try {
            // Submit the form with validated data
            $submission = $this->formService->submitForm($form, $participant, $request->validated());

            return redirect()->route('public.forms.slug.show', ['slug' => $slug])
                ->with('success', 'Formulario enviado exitosamente.');
                
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al enviar el formulario: ' . $e->getMessage())
                ->withInput();
        }
    }
}