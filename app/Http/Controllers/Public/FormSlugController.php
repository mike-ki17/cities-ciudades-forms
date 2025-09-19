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

        $user = $request->user();
        $participant = $user->participant ?? null;
        $hasSubmitted = false;
        $latestSubmission = null;

        // Check if user has already submitted this form
        if ($user) {
            $hasSubmitted = $user->hasSubmittedForm($form->id);
            
            if ($hasSubmitted) {
                $latestSubmission = $user->formSubmissions()
                    ->where('form_id', $form->id)
                    ->latest()
                    ->first();
            }
        }

        return view('public.forms.show', compact('form', 'participant', 'hasSubmitted', 'latestSubmission', 'user'));
    }
}