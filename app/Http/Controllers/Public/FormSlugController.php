<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\FormService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormSlugController extends Controller
{
    public function __construct(
        private FormService $formService
    ) {}

    /**
     * Display the form using its slug.
     */
    public function show(Request $request, string $slug): View
    {
        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form) {
            abort(404, 'Formulario no encontrado.');
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

        return view('public.forms.show', compact('form', 'participant', 'hasSubmitted', 'latestSubmission'));
    }
}