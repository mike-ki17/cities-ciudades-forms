<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\StoreFormRequest;
use App\Http\Requests\Form\UpdateFormRequest;
use App\Models\Event;
use App\Models\Form;
use App\Models\FormCategory;
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
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->get('event_id'));
        }

        // Filtro por estado (activo/inactivo)
        if ($request->filled('status')) {
            $status = $request->get('status') === 'active';
            $query->where('is_active', $status);
        }

        $forms = $query->orderBy('event_id')
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
        $validated = $request->validated();
        
        // Extract fields data from schema_json
        $fieldsData = $validated['schema_json']['fields'] ?? [];
        
        // Remove schema_json from form data since we'll use relational structure
        $formData = collect($validated)->except('schema_json')->toArray();
        
        $form = $this->formService->createFormWithRelationalData($formData, $fieldsData);

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
        // Load relational data for forms that use the new structure
        $form->load(['formFieldOrders.fieldJson', 'formFieldOrders.formCategory.formOptions']);
        
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();
        
        // Get the appropriate schema JSON for editing
        $schemaJson = $form->getSchemaJsonForEditing();
        
        return view('admin.forms.edit', compact('form', 'events', 'schemaJson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $validated = $request->validated();
        
        // Extract fields data from schema_json
        $fieldsData = $validated['schema_json']['fields'] ?? [];
        
        // Remove schema_json from form data since we'll use relational structure
        $formData = collect($validated)->except('schema_json')->toArray();
        
        $this->formService->updateFormWithRelationalData($form, $formData, $fieldsData);

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

    /**
     * Get available fields for form creation.
     */
    public function getAvailableFields(Request $request)
    {
        $fields = FormCategory::with(['formOptions' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

        $formattedFields = $fields->map(function ($field) {
            return [
                'id' => $field->id,
                'code' => $field->code,
                'name' => $field->name,
                'description' => $field->description,
                'options' => $field->formOptions->map(function ($option) {
                    return [
                        'value' => $option->value,
                        'label' => $option->label,
                        'description' => $option->description,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'fields' => $formattedFields,
        ]);
    }
}