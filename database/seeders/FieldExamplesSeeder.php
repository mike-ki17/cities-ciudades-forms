<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldJson;
use App\Models\FormCategory;
use App\Models\FormOption;

class FieldExamplesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Creando campos de ejemplo con estructura JSON...');

        // Array de campos de ejemplo basados en los ejemplos proporcionados
        $fieldExamples = [
            [
                'key' => 'ciudad',
                'label' => 'Ciudad',
                'type' => 'select',
                'required' => true,
                'order' => 3,
                'description' => 'Seleccione su ciudad',
                'options' => [
                    ['value' => 'bogota', 'label' => 'Bogotá D.C.', 'description' => null],
                    ['value' => 'medellin', 'label' => 'Medellín', 'description' => null],
                    ['value' => 'cali', 'label' => 'Cali', 'description' => null],
                    ['value' => 'barranquilla', 'label' => 'Barranquilla', 'description' => null],
                    ['value' => 'cartagena', 'label' => 'Cartagena', 'description' => null],
                    ['value' => 'bucaramanga', 'label' => 'Bucaramanga', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_bogota',
                'label' => 'Localidad',
                'type' => 'select',
                'required' => true,
                'order' => 4,
                'description' => 'Seleccione su localidad en Bogotá',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'bogota',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'usaquen', 'label' => 'Usaquén', 'description' => null],
                    ['value' => 'chapinero', 'label' => 'Chapinero', 'description' => null],
                    ['value' => 'santa_fe', 'label' => 'Santa Fe', 'description' => null],
                    ['value' => 'san_cristobal', 'label' => 'San Cristóbal', 'description' => null],
                    ['value' => 'usme', 'label' => 'Usme', 'description' => null],
                    ['value' => 'tunjuelito', 'label' => 'Tunjuelito', 'description' => null],
                    ['value' => 'bosa', 'label' => 'Bosa', 'description' => null],
                    ['value' => 'kennedy', 'label' => 'Kennedy', 'description' => null],
                    ['value' => 'fontibon', 'label' => 'Fontibón', 'description' => null],
                    ['value' => 'engativa', 'label' => 'Engativá', 'description' => null],
                    ['value' => 'suba', 'label' => 'Suba', 'description' => null],
                    ['value' => 'barrios_unidos', 'label' => 'Barrios Unidos', 'description' => null],
                    ['value' => 'teusaquillo', 'label' => 'Teusaquillo', 'description' => null],
                    ['value' => 'martires', 'label' => 'Los Mártires', 'description' => null],
                    ['value' => 'antonio_narino', 'label' => 'Antonio Nariño', 'description' => null],
                    ['value' => 'puente_aranda', 'label' => 'Puente Aranda', 'description' => null],
                    ['value' => 'candelaria', 'label' => 'La Candelaria', 'description' => null],
                    ['value' => 'rafael_uribe', 'label' => 'Rafael Uribe Uribe', 'description' => null],
                    ['value' => 'ciudad_bolivar', 'label' => 'Ciudad Bolívar', 'description' => null],
                    ['value' => 'sumapaz', 'label' => 'Sumapaz', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_medellin',
                'label' => 'Comuna',
                'type' => 'select',
                'required' => true,
                'order' => 5,
                'description' => 'Seleccione su comuna en Medellín',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'medellin',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'popular', 'label' => 'Popular', 'description' => null],
                    ['value' => 'santa_cruz', 'label' => 'Santa Cruz', 'description' => null],
                    ['value' => 'manrique', 'label' => 'Manrique', 'description' => null],
                    ['value' => 'aranjuez', 'label' => 'Aranjuez', 'description' => null],
                    ['value' => 'castilla', 'label' => 'Castilla', 'description' => null],
                    ['value' => 'doce_octubre', 'label' => 'Doce de Octubre', 'description' => null],
                    ['value' => 'robledo', 'label' => 'Robledo', 'description' => null],
                    ['value' => 'villa_hermosa', 'label' => 'Villa Hermosa', 'description' => null],
                    ['value' => 'buenavista', 'label' => 'Buenavista', 'description' => null],
                    ['value' => 'la_candelaria', 'label' => 'La Candelaria', 'description' => null],
                    ['value' => 'laureles', 'label' => 'Laureles-Estadio', 'description' => null],
                    ['value' => 'la_america', 'label' => 'La América', 'description' => null],
                    ['value' => 'san_javier', 'label' => 'San Javier', 'description' => null],
                    ['value' => 'el_poblado', 'label' => 'El Poblado', 'description' => null],
                    ['value' => 'guayabal', 'label' => 'Guayabal', 'description' => null],
                    ['value' => 'belen', 'label' => 'Belén', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_cali',
                'label' => 'Comuna',
                'type' => 'select',
                'required' => true,
                'order' => 6,
                'description' => 'Seleccione su comuna en Cali',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'cali',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'comuna_1', 'label' => 'Comuna 1 - Popular', 'description' => null],
                    ['value' => 'comuna_2', 'label' => 'Comuna 2 - Santa Rita', 'description' => null],
                    ['value' => 'comuna_3', 'label' => 'Comuna 3 - Sucre', 'description' => null],
                    ['value' => 'comuna_4', 'label' => 'Comuna 4 - Aranjuez', 'description' => null],
                    ['value' => 'comuna_5', 'label' => 'Comuna 5 - Castilla', 'description' => null],
                    ['value' => 'comuna_6', 'label' => 'Comuna 6 - Doce de Octubre', 'description' => null],
                    ['value' => 'comuna_7', 'label' => 'Comuna 7 - Robledo', 'description' => null],
                    ['value' => 'comuna_8', 'label' => 'Comuna 8 - Villa Hermosa', 'description' => null],
                    ['value' => 'comuna_9', 'label' => 'Comuna 9 - Buenos Aires', 'description' => null],
                    ['value' => 'comuna_10', 'label' => 'Comuna 10 - La Candelaria', 'description' => null],
                    ['value' => 'comuna_11', 'label' => 'Comuna 11 - Laureles', 'description' => null],
                    ['value' => 'comuna_12', 'label' => 'Comuna 12 - La América', 'description' => null],
                    ['value' => 'comuna_13', 'label' => 'Comuna 13 - San Javier', 'description' => null],
                    ['value' => 'comuna_14', 'label' => 'Comuna 14 - El Poblado', 'description' => null],
                    ['value' => 'comuna_15', 'label' => 'Comuna 15 - Guayabal', 'description' => null],
                    ['value' => 'comuna_16', 'label' => 'Comuna 16 - Belén', 'description' => null],
                    ['value' => 'comuna_17', 'label' => 'Comuna 17 - Villa Hermosa', 'description' => null],
                    ['value' => 'comuna_18', 'label' => 'Comuna 18 - Buenos Aires', 'description' => null],
                    ['value' => 'comuna_19', 'label' => 'Comuna 19 - La Candelaria', 'description' => null],
                    ['value' => 'comuna_20', 'label' => 'Comuna 20 - Laureles', 'description' => null],
                    ['value' => 'comuna_21', 'label' => 'Comuna 21 - La América', 'description' => null],
                    ['value' => 'comuna_22', 'label' => 'Comuna 22 - San Javier', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_barranquilla',
                'label' => 'Localidad',
                'type' => 'select',
                'required' => true,
                'order' => 7,
                'description' => 'Seleccione su localidad en Barranquilla',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'barranquilla',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'riomar', 'label' => 'Riomar', 'description' => null],
                    ['value' => 'norte_centro_historico', 'label' => 'Norte Centro Histórico', 'description' => null],
                    ['value' => 'sur_occidente', 'label' => 'Sur Occidente', 'description' => null],
                    ['value' => 'metropolitana', 'label' => 'Metropolitana', 'description' => null],
                    ['value' => 'suroriente', 'label' => 'Suroriente', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_cartagena',
                'label' => 'Localidad',
                'type' => 'select',
                'required' => true,
                'order' => 8,
                'description' => 'Seleccione su localidad en Cartagena',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'cartagena',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'historia_y_caribe', 'label' => 'Historia y Caribe Norte', 'description' => null],
                    ['value' => 'de_la_virgen_y_turistica', 'label' => 'De la Virgen y Turística', 'description' => null],
                    ['value' => 'industrial_y_de_la_bahia', 'label' => 'Industrial y de la Bahía', 'description' => null]
                ]
            ],
            [
                'key' => 'localidad_bucaramanga',
                'label' => 'Comuna',
                'type' => 'select',
                'required' => true,
                'order' => 9,
                'description' => 'Seleccione su comuna en Bucaramanga',
                'visible' => [
                    'model' => 'ciudad',
                    'value' => 'bucaramanga',
                    'condition' => 'equal'
                ],
                'options' => [
                    ['value' => 'norte', 'label' => 'Norte', 'description' => null],
                    ['value' => 'nororiente', 'label' => 'Nororiente', 'description' => null],
                    ['value' => 'santander', 'label' => 'Santander', 'description' => null],
                    ['value' => 'garcia_rovirosa', 'label' => 'García Rovira', 'description' => null],
                    ['value' => 'convencion', 'label' => 'Convención', 'description' => null],
                    ['value' => 'lacides_castro', 'label' => 'Lácides Castro', 'description' => null],
                    ['value' => 'mutis', 'label' => 'Mutis', 'description' => null],
                    ['value' => 'morrorico', 'label' => 'Morrorico', 'description' => null],
                    ['value' => 'sur', 'label' => 'Sur', 'description' => null],
                    ['value' => 'suroccidente', 'label' => 'Suroccidente', 'description' => null],
                    ['value' => 'occidente', 'label' => 'Occidente', 'description' => null],
                    ['value' => 'provenza', 'label' => 'Provenza', 'description' => null],
                    ['value' => 'cabecera', 'label' => 'Cabecera del Llano', 'description' => null],
                    ['value' => 'centro', 'label' => 'Centro', 'description' => null],
                    ['value' => 'oriental', 'label' => 'Oriental', 'description' => null],
                    ['value' => 'pedregosa', 'label' => 'Pedregosa', 'description' => null],
                    ['value' => 'sureste', 'label' => 'Sureste', 'description' => null]
                ]
            ]
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($fieldExamples as $fieldData) {
            // Verificar si el campo ya existe
            $existingField = FieldJson::where('key', $fieldData['key'])->first();
            
            if ($existingField) {
                $this->command->info("⏭️  Campo '{$fieldData['key']}' ya existe, omitiendo...");
                $skippedCount++;
                continue;
            }

            // Crear el campo en la tabla fields_json
            $field = FieldJson::create([
                'key' => $fieldData['key'],
                'label' => $fieldData['label'],
                'type' => $fieldData['type'],
                'required' => $fieldData['required'],
                'placeholder' => null,
                'validations' => [],
                'visible' => $fieldData['visible'] ?? null,
                'default_value' => null,
                'description' => $fieldData['description'],
                'is_active' => true,
            ]);

            // Crear una categoría para este campo
            $fieldCategory = FormCategory::create([
                'code' => $fieldData['key'],
                'name' => $fieldData['label'],
                'description' => 'Categoría para el campo: ' . $fieldData['label'],
                'is_active' => true,
            ]);

            // Crear las opciones si existen
            if (isset($fieldData['options']) && is_array($fieldData['options'])) {
                $order = 1;
                foreach ($fieldData['options'] as $option) {
                    FormOption::create([
                        'category_id' => $fieldCategory->id,
                        'value' => $option['value'],
                        'label' => $option['label'],
                        'order' => $order++,
                        'description' => $option['description'] ?? null,
                        'is_active' => true,
                    ]);
                }
            }

            $this->command->info("✅ Campo '{$fieldData['key']}' creado exitosamente con " . count($fieldData['options'] ?? []) . " opciones");
            $createdCount++;
        }

        $this->command->info("\n🎉 Resumen de la creación de campos:");
        $this->command->info("   📊 Campos creados: {$createdCount}");
        $this->command->info("   ⏭️  Campos omitidos: {$skippedCount}");
        $this->command->info("   📋 Total de campos procesados: " . count($fieldExamples));
        
        $this->command->info("\n🏙️ Campos creados:");
        foreach ($fieldExamples as $fieldData) {
            $status = FieldJson::where('key', $fieldData['key'])->exists() ? '✅' : '❌';
            $optionsCount = count($fieldData['options'] ?? []);
            $visibility = isset($fieldData['visible']) ? ' (condicional)' : '';
            $this->command->info("   {$status} {$fieldData['key']} - {$fieldData['label']} ({$optionsCount} opciones){$visibility}");
        }
        
        $this->command->info("\n🎯 Características de los campos creados:");
        $this->command->info("   • Campo principal: 'ciudad' con 6 opciones");
        $this->command->info("   • Campos condicionales: 6 campos de localidades");
        $this->command->info("   • Visibilidad condicional: Basada en el valor de 'ciudad'");
        $this->command->info("   • Total de opciones: " . array_sum(array_map(fn($f) => count($f['options'] ?? []), $fieldExamples)));
        
        $this->command->info("\n🌐 Acceso a los campos:");
        $this->command->info("   • Lista de campos: http://localhost:8000/admin/fields-json");
        $this->command->info("   • Crear nuevo campo: http://localhost:8000/admin/fields-json/create");
        
        $this->command->info("\n🎉 ¡Campos de ejemplo creados exitosamente!");
    }
}
