<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Event;
use Illuminate\Support\Str;

class ImprovedCiudadesLocalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un evento para el formulario
        $event = Event::first();
        if (!$event) {
            $event = Event::create([
                'name' => 'Evento de Ubicación',
                'city' => 'Bogotá',
                'year' => 2025,
                'description' => 'Evento para probar formulario de ciudades y localidades',
                'is_active' => true,
            ]);
        }

        // Crear el formulario con la nueva estructura mejorada
        $formData = [
            'event_id' => $event->id,
            'name' => 'Formulario de Ubicación - Ciudades y Localidades (Mejorado)',
            'slug' => 'formulario-ubicacion-mejorado-' . time(),
            'description' => 'Formulario con campos dinámicos donde al seleccionar una ciudad se cargan las localidades correspondientes desde la API',
            'is_active' => true,
        ];

        // Nueva estructura con un solo campo de localidad que se carga dinámicamente
        $fieldsData = [
            [
                'key' => 'nombre_completo',
                'label' => 'Nombre Completo',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Ingrese su nombre completo',
                'validations' => [
                    'min_length' => 2,
                    'max_length' => 100
                ],
                'description' => 'Su nombre completo'
            ],
            [
                'key' => 'email',
                'label' => 'Correo Electrónico',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'usuario@ejemplo.com',
                'validations' => [
                    'format' => 'email'
                ],
                'description' => 'Su correo electrónico'
            ],
            [
                'key' => 'ciudad',
                'label' => 'Ciudad',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'bogota', 'label' => 'Bogotá D.C.'],
                    ['value' => 'medellin', 'label' => 'Medellín'],
                    ['value' => 'cali', 'label' => 'Cali'],
                    ['value' => 'barranquilla', 'label' => 'Barranquilla'],
                    ['value' => 'cartagena', 'label' => 'Cartagena'],
                    ['value' => 'bucaramanga', 'label' => 'Bucaramanga']
                ],
                'description' => 'Seleccione su ciudad'
            ],
            // Campo único de localidad que se carga dinámicamente
            [
                'key' => 'localidad',
                'label' => 'Localidad/Comuna',
                'type' => 'select',
                'required' => true,
                'options' => [], // Se cargará dinámicamente desde la API
                'visible' => [
                    'model' => 'ciudad',
                    'value' => '', // Se mostrará cuando se seleccione cualquier ciudad
                    'condition' => 'not_equal'
                ],
                'description' => 'Seleccione su localidad o comuna',
                'dynamic_options' => true, // Flag para indicar que las opciones son dinámicas
                'api_endpoint' => '/api/localities/' // Endpoint para cargar opciones
            ],
            [
                'key' => 'direccion',
                'label' => 'Dirección',
                'type' => 'textarea',
                'required' => true,
                'placeholder' => 'Ingrese su dirección completa',
                'validations' => [
                    'min_length' => 10,
                    'max_length' => 200
                ],
                'description' => 'Su dirección completa'
            ],
            [
                'key' => 'telefono',
                'label' => 'Teléfono',
                'type' => 'tel',
                'required' => true,
                'placeholder' => '+57 300 123 4567',
                'validations' => [
                    'pattern' => '^\\+?[1-9]\\d{1,14}$'
                ],
                'description' => 'Su número de teléfono'
            ],
            [
                'key' => 'acepta_terminos',
                'label' => 'Acepto los términos y condiciones',
                'type' => 'checkbox',
                'required' => true,
                'validations' => [
                    'required' => true
                ],
                'description' => 'Debe aceptar los términos para continuar'
            ]
        ];

        // Usar el FormService para crear el formulario con estructura relacional
        $formService = app(\App\Services\FormService::class);
        $form = $formService->createFormWithRelationalData($formData, $fieldsData);

        $this->command->info("✅ Formulario de Ciudades y Localidades MEJORADO creado exitosamente:");
        $this->command->info("   📋 Nombre: {$form->name}");
        $this->command->info("   🔗 Slug: {$form->slug}");
        $this->command->info("   📊 Total de campos: " . count($fieldsData));
        $this->command->info("   🌐 URL de acceso: http://localhost:8000/form/{$form->slug}");
        $this->command->info("   🎯 URL de administración: http://localhost:8000/admin/forms/{$form->id}");
        
        $this->command->info("\n🏙️ Mejoras implementadas:");
        $this->command->info("   • Campo único 'localidad' en lugar de campos separados por ciudad");
        $this->command->info("   • Carga dinámica de opciones desde API endpoint");
        $this->command->info("   • Solo se guardan los datos relevantes (sin campos vacíos)");
        $this->command->info("   • Mejor experiencia de usuario");
        
        $this->command->info("\n🎯 Características del formulario mejorado:");
        $this->command->info("   • Campo principal: Select de ciudades");
        $this->command->info("   • Campo dinámico: Select de localidades (se carga según ciudad)");
        $this->command->info("   • API endpoint: /api/localities/{city}");
        $this->command->info("   • Validaciones: Campos requeridos y formatos");
        $this->command->info("   • Comportamiento: Localidades se cargan dinámicamente");
        
        $this->command->info("\n🎉 ¡Formulario mejorado listo para probar campos dinámicos!");
    }
}
