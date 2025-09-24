<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FieldJson;
use App\Models\FormCategory;
use App\Models\FormOption;

class CampoDinamicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creando campo dinámico Ciudad + Localidad...');

        // Verificar si el campo ya existe
        $existingField = FieldJson::where('key', 'ubicacion')->first();
        
        if ($existingField) {
            $this->command->info("⏭️  Campo 'ubicacion' ya existe, omitiendo...");
            return;
        }

        // Crear el campo dinámico
        $field = FieldJson::create([
            'key' => 'ubicacion',
            'label' => 'Ubicación',
            'type' => 'dynamic_select',
            'required' => true,
            'placeholder' => null,
            'validations' => [],
            'visible' => null,
            'dynamic_options' => [
                'api_endpoint' => '/api/localities/{city}',
                'parent_field' => 'ciudad',
                'child_field' => 'localidad'
            ],
            'default_value' => null,
            'description' => 'Seleccione su ciudad y localidad',
            'is_active' => true,
        ]);

        // Crear una categoría para este campo
        $fieldCategory = FormCategory::create([
            'code' => 'ubicacion',
            'name' => 'Ubicación',
            'description' => 'Categoría para el campo dinámico de ubicación',
            'is_active' => true,
        ]);

        // Crear las opciones de ciudades (campo padre)
        $ciudades = [
            ['value' => 'bogota', 'label' => 'Bogotá D.C.', 'description' => null],
            ['value' => 'medellin', 'label' => 'Medellín', 'description' => null],
            ['value' => 'cali', 'label' => 'Cali', 'description' => null],
            ['value' => 'barranquilla', 'label' => 'Barranquilla', 'description' => null],
            ['value' => 'cartagena', 'label' => 'Cartagena', 'description' => null],
            ['value' => 'bucaramanga', 'label' => 'Bucaramanga', 'description' => null]
        ];

        $order = 1;
        foreach ($ciudades as $ciudad) {
            FormOption::create([
                'category_id' => $fieldCategory->id,
                'value' => $ciudad['value'],
                'label' => $ciudad['label'],
                'order' => $order++,
                'description' => $ciudad['description'],
                'is_active' => true,
            ]);
        }

        $this->command->info("✅ Campo dinámico 'ubicacion' creado exitosamente");
        $this->command->info("   📊 Tipo: dynamic_select");
        $this->command->info("   🎯 API Endpoint: /api/localities/{city}");
        $this->command->info("   🏙️ Ciudades: " . count($ciudades));
        $this->command->info("   🔗 Campo padre: ciudad");
        $this->command->info("   🔗 Campo hijo: localidad");
        
        $this->command->info("\n🎉 ¡Campo dinámico creado exitosamente!");
        $this->command->info("🌐 Accede al campo en: http://localhost:8000/admin/fields-json");
        $this->command->info("📋 Este campo cargará las localidades dinámicamente desde la API");
    }
}
