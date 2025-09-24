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

        // For public access, we don't need user authentication
        $user = null;
        $participant = null;
        $hasSubmitted = false;
        $latestSubmission = null;

        // Check if there's a participant in session (from previous form submission)
        $participantId = $request->session()->get('participant_id');
        if ($participantId) {
            $participant = \App\Models\Participant::find($participantId);
            if ($participant) {
                $hasSubmitted = $this->formService->hasParticipantSubmitted($form, $participant);
                if ($hasSubmitted) {
                    $latestSubmission = $this->formService->getLatestParticipantSubmission($form, $participant);
                    
                    // Si el formulario ya fue enviado, mostrar la vista de éxito
                    return view('public.forms.success', compact('form', 'participant', 'hasSubmitted', 'latestSubmission', 'user'));
                }
            }
        }

        return view('public.forms.show', compact('form', 'participant', 'hasSubmitted', 'latestSubmission', 'user'));
    }
}