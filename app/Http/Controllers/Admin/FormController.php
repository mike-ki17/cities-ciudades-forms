<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Models\City;
use App\Models\Form;
use App\Services\FormService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormController extends Controller
{
    public function __construct(
        private FormService $formService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $forms = Form::with('city')
            ->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->paginate(15);

        return view('admin.forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cities = City::orderBy('name')->get();
        return view('admin.forms.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request): RedirectResponse
    {
        $form = $this->formService->createForm($request->validated());

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form): View
    {
        $form->load('city', 'formSubmissions.participant');
        
        return view('admin.forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form): View
    {
        $cities = City::orderBy('name')->get();
        return view('admin.forms.edit', compact('form', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $this->formService->updateForm($form, $request->validated());

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form): RedirectResponse
    {
        $form->delete();

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulario eliminado exitosamente.');
    }

    /**
     * Activate the specified form.
     */
    public function activate(Form $form): RedirectResponse
    {
        $this->formService->activateForm($form);

        return redirect()->back()
            ->with('success', 'Formulario activado exitosamente.');
    }

    /**
     * Deactivate the specified form.
     */
    public function deactivate(Form $form): RedirectResponse
    {
        $this->formService->deactivateForm($form);

        return redirect()->back()
            ->with('success', 'Formulario desactivado exitosamente.');
    }
}