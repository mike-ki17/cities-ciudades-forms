<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormCategory;
use App\Models\FormOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FieldController extends Controller
{
    /**
     * Display a listing of the form categories (fields).
     */
    public function index()
    {
        $categories = FormCategory::withCount('formOptions')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.fields.index', compact('categories'));
    }

    /**
     * Show the form for creating a new form category.
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created form category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:form_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FormCategory::create($validated);

        return redirect()->route('admin.fields.index')
            ->with('success', 'Campo creado exitosamente.');
    }

    /**
     * Display the specified form category.
     */
    public function show(FormCategory $field)
    {
        $field->load(['formOptions' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('admin.fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified form category.
     */
    public function edit(FormCategory $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified form category in storage.
     */
    public function update(Request $request, FormCategory $field)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('form_categories', 'code')->ignore($field->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $field->update($validated);

        return redirect()->route('admin.fields.index')
            ->with('success', 'Campo actualizado exitosamente.');
    }

    /**
     * Remove the specified form category from storage.
     */
    public function destroy(FormCategory $field)
    {
        // Check if the field has options
        if ($field->formOptions()->count() > 0) {
            return redirect()->route('admin.fields.index')
                ->with('error', 'No se puede eliminar el campo porque tiene opciones asociadas. Elimina primero las opciones.');
        }

        $field->delete();

        return redirect()->route('admin.fields.index')
            ->with('success', 'Campo eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a form category.
     */
    public function toggleStatus(FormCategory $field)
    {
        $field->update(['is_active' => !$field->is_active]);

        $status = $field->is_active ? 'activado' : 'desactivado';

        return redirect()->route('admin.fields.index')
            ->with('success', "Campo {$status} exitosamente.");
    }

    /**
     * Display a listing of options for a specific field.
     */
    public function options(FormCategory $field)
    {
        $options = $field->formOptions()
            ->orderBy('order')
            ->paginate(15);

        return view('admin.fields.options.index', compact('field', 'options'));
    }

    /**
     * Show the form for creating a new option for a field.
     */
    public function createOption(FormCategory $field)
    {
        return view('admin.fields.options.create', compact('field'));
    }

    /**
     * Store a newly created option for a field.
     */
    public function storeOption(Request $request, FormCategory $field)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['category_id'] = $field->id;
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? ($field->formOptions()->max('order') + 1);

        FormOption::create($validated);

        return redirect()->route('admin.fields.options', $field)
            ->with('success', 'Opción creada exitosamente.');
    }

    /**
     * Show the form for editing an option.
     */
    public function editOption(FormCategory $field, FormOption $option)
    {
        return view('admin.fields.options.edit', compact('field', 'option'));
    }

    /**
     * Update the specified option.
     */
    public function updateOption(Request $request, FormCategory $field, FormOption $option)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $option->update($validated);

        return redirect()->route('admin.fields.options', $field)
            ->with('success', 'Opción actualizada exitosamente.');
    }

    /**
     * Remove the specified option from storage.
     */
    public function destroyOption(FormCategory $field, FormOption $option)
    {
        $option->delete();

        return redirect()->route('admin.fields.options', $field)
            ->with('success', 'Opción eliminada exitosamente.');
    }

    /**
     * Toggle the active status of an option.
     */
    public function toggleOptionStatus(FormCategory $field, FormOption $option)
    {
        $option->update(['is_active' => !$option->is_active]);

        $status = $option->is_active ? 'activada' : 'desactivada';

        return redirect()->route('admin.fields.options', $field)
            ->with('success', "Opción {$status} exitosamente.");
    }

    /**
     * Update the order of options.
     */
    public function updateOptionOrder(Request $request, FormCategory $field)
    {
        $request->validate([
            'options' => 'required|array',
            'options.*.id' => 'required|exists:form_options,id',
            'options.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->options as $optionData) {
            FormOption::where('id', $optionData['id'])
                ->where('category_id', $field->id)
                ->update(['order' => $optionData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
