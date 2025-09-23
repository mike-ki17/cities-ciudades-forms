<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Event;
use Illuminate\Support\Str;

class CiudadesLocalidadesSeeder extends Seeder
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

        // Crear el formulario con ciudades y localidades condicionales usando el nuevo sistema relacional
        $formData = [
            'event_id' => $event->id,
            'name' => 'Formulario de Ubicación - Ciudades y Localidades',
            'slug' => 'formulario-ubicacion-' . time(),
            'description' => 'Formulario con campos condicionales donde al seleccionar una ciudad se muestran las localidades correspondientes',
            'is_active' => true,
        ];

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
                    // Localidades de Bogotá
                    [
                        'key' => 'localidad_bogota',
                        'label' => 'Localidad',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'usaquen', 'label' => 'Usaquén'],
                            ['value' => 'chapinero', 'label' => 'Chapinero'],
                            ['value' => 'santa_fe', 'label' => 'Santa Fe'],
                            ['value' => 'san_cristobal', 'label' => 'San Cristóbal'],
                            ['value' => 'usme', 'label' => 'Usme'],
                            ['value' => 'tunjuelito', 'label' => 'Tunjuelito'],
                            ['value' => 'bosa', 'label' => 'Bosa'],
                            ['value' => 'kennedy', 'label' => 'Kennedy'],
                            ['value' => 'fontibon', 'label' => 'Fontibón'],
                            ['value' => 'engativa', 'label' => 'Engativá'],
                            ['value' => 'suba', 'label' => 'Suba'],
                            ['value' => 'barrios_unidos', 'label' => 'Barrios Unidos'],
                            ['value' => 'teusaquillo', 'label' => 'Teusaquillo'],
                            ['value' => 'martires', 'label' => 'Los Mártires'],
                            ['value' => 'antonio_narino', 'label' => 'Antonio Nariño'],
                            ['value' => 'puente_aranda', 'label' => 'Puente Aranda'],
                            ['value' => 'candelaria', 'label' => 'La Candelaria'],
                            ['value' => 'rafael_uribe', 'label' => 'Rafael Uribe Uribe'],
                            ['value' => 'ciudad_bolivar', 'label' => 'Ciudad Bolívar'],
                            ['value' => 'sumapaz', 'label' => 'Sumapaz']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'bogota',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su localidad en Bogotá'
                    ],
                    // Comunas de Medellín
                    [
                        'key' => 'localidad_medellin',
                        'label' => 'Comuna',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'popular', 'label' => 'Popular'],
                            ['value' => 'santa_cruz', 'label' => 'Santa Cruz'],
                            ['value' => 'manrique', 'label' => 'Manrique'],
                            ['value' => 'aranjuez', 'label' => 'Aranjuez'],
                            ['value' => 'castilla', 'label' => 'Castilla'],
                            ['value' => 'doce_octubre', 'label' => 'Doce de Octubre'],
                            ['value' => 'robledo', 'label' => 'Robledo'],
                            ['value' => 'villa_hermosa', 'label' => 'Villa Hermosa'],
                            ['value' => 'buenavista', 'label' => 'Buenavista'],
                            ['value' => 'la_candelaria', 'label' => 'La Candelaria'],
                            ['value' => 'laureles', 'label' => 'Laureles-Estadio'],
                            ['value' => 'la_america', 'label' => 'La América'],
                            ['value' => 'san_javier', 'label' => 'San Javier'],
                            ['value' => 'el_poblado', 'label' => 'El Poblado'],
                            ['value' => 'guayabal', 'label' => 'Guayabal'],
                            ['value' => 'belen', 'label' => 'Belén']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'medellin',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su comuna en Medellín'
                    ],
                    // Comunas de Cali
                    [
                        'key' => 'localidad_cali',
                        'label' => 'Comuna',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'comuna_1', 'label' => 'Comuna 1 - Popular'],
                            ['value' => 'comuna_2', 'label' => 'Comuna 2 - Santa Rita'],
                            ['value' => 'comuna_3', 'label' => 'Comuna 3 - Sucre'],
                            ['value' => 'comuna_4', 'label' => 'Comuna 4 - Aranjuez'],
                            ['value' => 'comuna_5', 'label' => 'Comuna 5 - Castilla'],
                            ['value' => 'comuna_6', 'label' => 'Comuna 6 - Doce de Octubre'],
                            ['value' => 'comuna_7', 'label' => 'Comuna 7 - Robledo'],
                            ['value' => 'comuna_8', 'label' => 'Comuna 8 - Villa Hermosa'],
                            ['value' => 'comuna_9', 'label' => 'Comuna 9 - Buenos Aires'],
                            ['value' => 'comuna_10', 'label' => 'Comuna 10 - La Candelaria'],
                            ['value' => 'comuna_11', 'label' => 'Comuna 11 - Laureles'],
                            ['value' => 'comuna_12', 'label' => 'Comuna 12 - La América'],
                            ['value' => 'comuna_13', 'label' => 'Comuna 13 - San Javier'],
                            ['value' => 'comuna_14', 'label' => 'Comuna 14 - El Poblado'],
                            ['value' => 'comuna_15', 'label' => 'Comuna 15 - Guayabal'],
                            ['value' => 'comuna_16', 'label' => 'Comuna 16 - Belén'],
                            ['value' => 'comuna_17', 'label' => 'Comuna 17 - Villa Hermosa'],
                            ['value' => 'comuna_18', 'label' => 'Comuna 18 - Buenos Aires'],
                            ['value' => 'comuna_19', 'label' => 'Comuna 19 - La Candelaria'],
                            ['value' => 'comuna_20', 'label' => 'Comuna 20 - Laureles'],
                            ['value' => 'comuna_21', 'label' => 'Comuna 21 - La América'],
                            ['value' => 'comuna_22', 'label' => 'Comuna 22 - San Javier']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'cali',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su comuna en Cali'
                    ],
                    // Localidades de Barranquilla
                    [
                        'key' => 'localidad_barranquilla',
                        'label' => 'Localidad',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'riomar', 'label' => 'Riomar'],
                            ['value' => 'norte_centro_historico', 'label' => 'Norte Centro Histórico'],
                            ['value' => 'sur_occidente', 'label' => 'Sur Occidente'],
                            ['value' => 'metropolitana', 'label' => 'Metropolitana'],
                            ['value' => 'suroriente', 'label' => 'Suroriente']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'barranquilla',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su localidad en Barranquilla'
                    ],
                    // Localidades de Cartagena
                    [
                        'key' => 'localidad_cartagena',
                        'label' => 'Localidad',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'historia_y_caribe', 'label' => 'Historia y Caribe Norte'],
                            ['value' => 'de_la_virgen_y_turistica', 'label' => 'De la Virgen y Turística'],
                            ['value' => 'industrial_y_de_la_bahia', 'label' => 'Industrial y de la Bahía']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'cartagena',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su localidad en Cartagena'
                    ],
                    // Comunas de Bucaramanga
                    [
                        'key' => 'localidad_bucaramanga',
                        'label' => 'Comuna',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'norte', 'label' => 'Norte'],
                            ['value' => 'nororiente', 'label' => 'Nororiente'],
                            ['value' => 'santander', 'label' => 'Santander'],
                            ['value' => 'garcia_rovirosa', 'label' => 'García Rovira'],
                            ['value' => 'convencion', 'label' => 'Convención'],
                            ['value' => 'lacides_castro', 'label' => 'Lácides Castro'],
                            ['value' => 'mutis', 'label' => 'Mutis'],
                            ['value' => 'morrorico', 'label' => 'Morrorico'],
                            ['value' => 'sur', 'label' => 'Sur'],
                            ['value' => 'suroccidente', 'label' => 'Suroccidente'],
                            ['value' => 'occidente', 'label' => 'Occidente'],
                            ['value' => 'provenza', 'label' => 'Provenza'],
                            ['value' => 'cabecera', 'label' => 'Cabecera del Llano'],
                            ['value' => 'centro', 'label' => 'Centro'],
                            ['value' => 'oriental', 'label' => 'Oriental'],
                            ['value' => 'pedregosa', 'label' => 'Pedregosa'],
                            ['value' => 'sureste', 'label' => 'Sureste']
                        ],
                        'visible' => [
                            'model' => 'ciudad',
                            'value' => 'bucaramanga',
                            'condition' => 'equal'
                        ],
                        'description' => 'Seleccione su comuna en Bucaramanga'
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

        $this->command->info("✅ Formulario de Ciudades y Localidades creado exitosamente:");
        $this->command->info("   📋 Nombre: {$form->name}");
        $this->command->info("   🔗 Slug: {$form->slug}");
        $this->command->info("   📊 Total de campos: " . count($fieldsData));
        $this->command->info("   🌐 URL de acceso: http://localhost:8000/form/{$form->slug}");
        $this->command->info("   🎯 URL de administración: http://localhost:8000/admin/forms/{$form->id}");
        
        $this->command->info("\n🏙️ Ciudades incluidas:");
        $ciudades = [
            'Bogotá D.C.' => '20 localidades',
            'Medellín' => '16 comunas',
            'Cali' => '22 comunas',
            'Barranquilla' => '5 localidades',
            'Cartagena' => '3 localidades',
            'Bucaramanga' => '17 comunas'
        ];
        
        foreach ($ciudades as $ciudad => $detalle) {
            $this->command->info("   • {$ciudad}: {$detalle}");
        }
        
        $this->command->info("\n🎯 Características del formulario:");
        $this->command->info("   • Campo principal: Select de ciudades");
        $this->command->info("   • Campos condicionales: Selects de localidades por ciudad");
        $this->command->info("   • Validaciones: Campos requeridos y formatos");
        $this->command->info("   • Comportamiento: Localidades aparecen según ciudad seleccionada");
        
        $this->command->info("\n🎉 ¡Formulario listo para probar campos condicionales!");
    }
}
