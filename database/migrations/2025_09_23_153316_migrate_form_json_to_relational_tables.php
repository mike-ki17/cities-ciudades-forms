<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Form;
use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\FormFieldOrder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing forms with schema_json
        $forms = Form::whereNotNull('schema_json')->get();
        
        foreach ($forms as $form) {
            $this->migrateFormToRelational($form);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the relational data in the correct order to avoid foreign key constraints
        FormFieldOrder::truncate();
        DB::table('parent_form_options')->truncate();
        FormOption::truncate();
        FormCategory::truncate();
        DB::table('fields_json')->truncate();
    }

    /**
     * Migrate a single form from JSON to relational structure
     */
    private function migrateFormToRelational(Form $form): void
    {
        $schema = $form->schema_json;
        
        if (!isset($schema['fields']) || !is_array($schema['fields'])) {
            return;
        }

        $fieldOrder = 1;

        foreach ($schema['fields'] as $fieldData) {
            // Create or get the field in fields_json table
            $fieldJson = DB::table('fields_json')->updateOrInsert(
                ['key' => $fieldData['key']],
                [
                    'key' => $fieldData['key'],
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'required' => $fieldData['required'] ?? false,
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'validations' => json_encode($fieldData['validations'] ?? []),
                    'visible' => json_encode($fieldData['visible'] ?? null),
                    'default_value' => $fieldData['default_value'] ?? null,
                    'description' => $fieldData['description'] ?? null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $fieldJsonId = DB::table('fields_json')->where('key', $fieldData['key'])->value('id');

            // Create a category for this specific field
            $fieldCategory = FormCategory::updateOrCreate(
                ['code' => $fieldData['key']],
                [
                    'name' => $fieldData['label'],
                    'description' => 'Categoría para el campo: ' . $fieldData['label'],
                    'is_active' => true,
                ]
            );

            // Create form field order entry
            FormFieldOrder::create([
                'form_id' => $form->id,
                'form_category_id' => $fieldCategory->id,
                'field_json_id' => $fieldJsonId,
                'order' => $fieldData['order'] ?? $fieldOrder++,
                'extra_data' => json_encode([
                    'original_field_data' => $fieldData
                ]),
            ]);

            // Handle select/checkbox options
            if (isset($fieldData['options']) && is_array($fieldData['options'])) {
                $this->migrateFieldOptions($fieldData, $fieldCategory);
            }
        }
    }

    /**
     * Migrate field options to form_options table
     */
    private function migrateFieldOptions(array $fieldData, FormCategory $category): void
    {
        $optionOrder = 1;

        foreach ($fieldData['options'] as $option) {
            $value = is_array($option) ? $option['value'] : $option;
            $label = is_array($option) ? ($option['label'] ?? $option['value']) : $option;

            FormOption::create([
                'category_id' => $category->id,
                'value' => $value,
                'label' => $label,
                'order' => $optionOrder++,
                'description' => is_array($option) ? ($option['description'] ?? null) : null,
                'is_active' => true,
            ]);
        }
    }
};
