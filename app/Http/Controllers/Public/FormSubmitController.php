<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\SubmitFormRequest;
use App\Models\City;
use App\Services\FormService;
use App\Services\ParticipantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FormSubmitController extends Controller
{
    public function __construct(
        private FormService $formService,
        private ParticipantService $participantService
    ) {}

    /**
     * Handle form submission.
     */
    public function store(SubmitFormRequest $request, string $city): RedirectResponse
    {
        $form = $this->formService->getActiveFormForCity($city);
        
        if (!$form) {
            abort(404, 'No se encontró un formulario activo para esta ciudad.');
        }

        // Get participant
        $participant = null;
        
        if (Auth::check() && Auth::user()->participant) {
            $participant = Auth::user()->participant;
        } else {
            // For non-authenticated users, we need to create a participant
            // This would require additional form fields for participant data
            abort(403, 'Debe estar autenticado para enviar formularios.');
        }

        // Check if participant has already submitted this form
        if ($this->formService->hasParticipantSubmitted($form, $participant)) {
            return redirect()->back()
                ->with('error', 'Ya has llenado este formulario anteriormente. No puedes enviarlo nuevamente.')
                ->withInput();
        }

        try {
            // Submit the form with validated data
            $submission = $this->formService->submitForm($form, $participant, $request->validated());

            return redirect('/')
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