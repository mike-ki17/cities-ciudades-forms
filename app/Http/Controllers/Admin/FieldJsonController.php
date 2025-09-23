<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Field\StoreFieldJsonRequest;
use App\Http\Requests\Field\UpdateFieldJsonRequest;
use App\Models\FieldJson;
use App\Models\FormCategory;
use App\Models\FormOption;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FieldJsonController extends Controller
{
    /**
     * Display a listing of JSON fields.
     */
    public function index(): View
    {
        $fields = FieldJson::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.fields-json.index', compact('fields'));
    }

    /**
     * Show the form for creating a new JSON field.
     */
    public function create(): View
    {
        return view('admin.fields-json.create');
    }

    /**
     * Store a newly created JSON field in storage.
     */
    public function store(StoreFieldJsonRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle field_json conversion if it's still a string
        if (isset($validated['field_json']) && is_string($validated['field_json'])) {
            $fieldArray = json_decode($validated['field_json'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['field_json'] = $fieldArray;
            } else {
                return redirect()->back()
                    ->with('error', 'Error en el formato JSON del campo: ' . json_last_error_msg())
                    ->withInput();
            }
        }

        // Extract field data from field_json
        $fieldData = $validated['field_json'];
        
        // Create the field in fields_json table
        $field = FieldJson::create([
            'key' => $fieldData['key'],
            'label' => $fieldData['label'],
            'type' => $fieldData['type'],
            'required' => $fieldData['required'] ?? false,
            'placeholder' => $fieldData['placeholder'] ?? null,
            'validations' => $fieldData['validations'] ?? [],
            'visible' => $fieldData['visible'] ?? null,
            'default_value' => $fieldData['default_value'] ?? null,
            'description' => $fieldData['description'] ?? null,
            'is_active' => true,
        ]);

        // Create a category for this field
        $fieldCategory = FormCategory::create([
            'code' => $fieldData['key'],
            'name' => $fieldData['label'],
            'description' => 'Categoría para el campo: ' . $fieldData['label'],
            'is_active' => true,
        ]);

        // Handle select/checkbox options
        if (isset($fieldData['options']) && is_array($fieldData['options'])) {
            $this->createFieldOptions($fieldCategory, $fieldData['options']);
        }

        return redirect()->route('admin.fields-json.index')
            ->with('success', 'Campo creado exitosamente.');
    }

    /**
     * Display the specified JSON field.
     */
    public function show(FieldJson $field): View
    {
        $field->load('formFieldOrders.formCategory.formOptions');
        
        return view('admin.fields-json.show', compact('field'));
    }

    /**
     * Show the form for editing the specified JSON field.
     */
    public function edit(FieldJson $field): View
    {
        return view('admin.fields-json.edit', compact('field'));
    }

    /**
     * Update the specified JSON field in storage.
     */
    public function update(UpdateFieldJsonRequest $request, FieldJson $field): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle field_json conversion if it's still a string
        if (isset($validated['field_json']) && is_string($validated['field_json'])) {
            $fieldArray = json_decode($validated['field_json'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $validated['field_json'] = $fieldArray;
            } else {
                return redirect()->back()
                    ->with('error', 'Error en el formato JSON del campo: ' . json_last_error_msg())
                    ->withInput();
            }
        }

        // Extract field data from field_json
        $fieldData = $validated['field_json'];
        
        // Update the field
        $field->update([
            'key' => $fieldData['key'],
            'label' => $fieldData['label'],
            'type' => $fieldData['type'],
            'required' => $fieldData['required'] ?? false,
            'placeholder' => $fieldData['placeholder'] ?? null,
            'validations' => $fieldData['validations'] ?? [],
            'visible' => $fieldData['visible'] ?? null,
            'default_value' => $fieldData['default_value'] ?? null,
            'description' => $fieldData['description'] ?? null,
        ]);

        // Update the category
        $fieldCategory = FormCategory::where('code', $field->key)->first();
        if ($fieldCategory) {
            $fieldCategory->update([
                'name' => $fieldData['label'],
                'description' => 'Categoría para el campo: ' . $fieldData['label'],
            ]);

            // Update options if they exist
            if (isset($fieldData['options']) && is_array($fieldData['options'])) {
                // Clear existing options
                $fieldCategory->formOptions()->delete();
                // Create new options
                $this->createFieldOptions($fieldCategory, $fieldData['options']);
            }
        }

        return redirect()->route('admin.fields-json.index')
            ->with('success', 'Campo actualizado exitosamente.');
    }

    /**
     * Remove the specified JSON field from storage.
     */
    public function destroy(FieldJson $field): RedirectResponse
    {
        // Check if the field is used in any forms
        if ($field->formFieldOrders()->count() > 0) {
            return redirect()->route('admin.fields-json.index')
                ->with('error', 'No se puede eliminar el campo porque está siendo utilizado en formularios.');
        }

        // Delete associated category and options
        $fieldCategory = FormCategory::where('code', $field->key)->first();
        if ($fieldCategory) {
            $fieldCategory->formOptions()->delete();
            $fieldCategory->delete();
        }

        $field->delete();

        return redirect()->route('admin.fields-json.index')
            ->with('success', 'Campo eliminado exitosamente.');
    }

    /**
     * Toggle the active status of a JSON field.
     */
    public function toggleStatus(FieldJson $field): RedirectResponse
    {
        $field->update(['is_active' => !$field->is_active]);

        $status = $field->is_active ? 'activado' : 'desactivado';

        return redirect()->route('admin.fields-json.index')
            ->with('success', "Campo {$status} exitosamente.");
    }

    /**
     * Upload CSV file for select field options.
     */
    public function uploadCsvOptions(Request $request): RedirectResponse
    {
        $request->validate([
            'field_id' => 'required|exists:fields_json,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $field = FieldJson::findOrFail($request->field_id);
        
        if ($field->type !== 'select') {
            return redirect()->back()
                ->with('error', 'Solo se pueden subir opciones CSV para campos de tipo select.');
        }

        try {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getPathname()));
            
            // Skip header row if it exists
            $header = array_shift($csvData);
            
            // Validate CSV structure
            if (count($header) < 2) {
                return redirect()->back()
                    ->with('error', 'El archivo CSV debe tener al menos 2 columnas: value, label.');
            }

            // Get or create the field category
            $fieldCategory = FormCategory::where('code', $field->key)->first();
            if (!$fieldCategory) {
                $fieldCategory = FormCategory::create([
                    'code' => $field->key,
                    'name' => $field->label,
                    'description' => 'Categoría para el campo: ' . $field->label,
                    'is_active' => true,
                ]);
            }

            // Clear existing options
            $fieldCategory->formOptions()->delete();

            // Create new options from CSV
            $order = 1;
            foreach ($csvData as $row) {
                if (count($row) >= 2 && !empty($row[0]) && !empty($row[1])) {
                    FormOption::create([
                        'category_id' => $fieldCategory->id,
                        'value' => trim($row[0]),
                        'label' => trim($row[1]),
                        'order' => $order++,
                        'description' => isset($row[2]) ? trim($row[2]) : null,
                        'is_active' => true,
                    ]);
                }
            }

            return redirect()->back()
                ->with('success', 'Opciones CSV cargadas exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar el archivo CSV: ' . $e->getMessage());
        }
    }

    /**
     * Create field options from array data.
     */
    private function createFieldOptions(FormCategory $fieldCategory, array $options): void
    {
        $order = 1;
        foreach ($options as $option) {
            if (is_array($option) && isset($option['value']) && isset($option['label'])) {
                FormOption::create([
                    'category_id' => $fieldCategory->id,
                    'value' => $option['value'],
                    'label' => $option['label'],
                    'order' => $order++,
                    'description' => $option['description'] ?? null,
                    'is_active' => $option['is_active'] ?? true,
                ]);
            } elseif (is_string($option)) {
                // Simple string option
                FormOption::create([
                    'category_id' => $fieldCategory->id,
                    'value' => $option,
                    'label' => $option,
                    'order' => $order++,
                    'is_active' => true,
                ]);
            }
        }
    }
}
