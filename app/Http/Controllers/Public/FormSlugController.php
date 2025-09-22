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
     * Display the form using its ID and slug.
     */
    public function show(Request $request, int $id, string $slug): View
    {
        $form = $this->formService->getFormBySlug($slug);
        
        if (!$form || $form->id !== $id) {
            abort(404, 'Formulario no encontrado.');
        }

        // For public access, we don't need user authentication
        $user = null;
        $participant = null;
        $hasSubmitted = false;
        $latestSubmission = null;

        return view('public.forms.show', compact('form', 'participant', 'hasSubmitted', 'latestSubmission', 'user'));
    }
}