<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Models\Event;
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
    public function index(Request $request): View
    {
        $query = Form::with('event');

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('event', function ($eventQuery) use ($search) {
                      $eventQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('city', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por evento específico
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->get('city_id'));
        }

        // Filtro por estado (activo/inactivo)
        if ($request->filled('status')) {
            $status = $request->get('status') === 'active';
            $query->where('is_active', $status);
        }

        $forms = $query->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->paginate(15)
            ->withQueryString(); // Mantener parámetros de búsqueda en la paginación

        // Obtener todos los eventos para el filtro
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();

        return view('admin.forms.index', compact('forms', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();
        return view('admin.forms.create', compact('events'));
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
        $form->load('event', 'formSubmissions.participant');
        
        return view('admin.forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form): View
    {
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();
        return view('admin.forms.edit', compact('form', 'events'));
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