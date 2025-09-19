<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormPageController extends Controller
{
    public function __construct(
        private FormService $formService
    ) {}

    /**
     * Display the form for a specific city.
     */
    public function show(Request $request, string $city): View
    {
        $form = $this->formService->getActiveFormForCity($city);
        
        if (!$form) {
            abort(404, 'No se encontró un formulario activo para esta ciudad.');
        }

        $participant = null;
        $hasSubmitted = false;
        $latestSubmission = null;

        // If user is authenticated and has a participant
        if ($request->user() && $request->user()->participant) {
            $participant = $request->user()->participant;
            $hasSubmitted = $this->formService->hasParticipantSubmitted($form, $participant);
            
            if ($hasSubmitted) {
                $latestSubmission = $this->formService->getLatestSubmission($form, $participant);
            }
        }

        return view('public.forms.show', compact('form', 'city', 'participant', 'hasSubmitted', 'latestSubmission'));
    }
}