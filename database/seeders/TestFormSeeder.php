<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Form;
use Illuminate\Database\Seeder;

class TestFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first event or create a test event
        $event = Event::first();
        
        if (!$event) {
            $event = Event::create([
                'name' => 'Smart Films Festival',
                'city' => 'Bogotá',
                'year' => 2024,
            ]);
        }

        // Create a test form
        $form = Form::create([
            'city_id' => $event->id,
            'name' => 'Formulario de Prueba - URLs Públicas',
            'description' => 'Formulario para probar la funcionalidad de URLs públicas sin autenticación',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Nombre Completo',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Ingresa tu nombre completo',
                        'validations' => [
                            'max_elements' => 100
                        ]
                    ],
                    [
                        'key' => 'email',
                        'label' => 'Correo Electrónico',
                        'type' => 'email',
                        'required' => true,
                        'placeholder' => 'tu@email.com',
                        'validations' => [
                            'max_elements' => 50
                        ]
                    ],
                    [
                        'key' => 'phone',
                        'label' => 'Teléfono',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'Tu número de teléfono',
                        'validations' => [
                            'max_elements' => 20
                        ]
                    ],
                    [
                        'key' => 'message',
                        'label' => 'Mensaje',
                        'type' => 'textarea',
                        'required' => true,
                        'placeholder' => 'Escribe tu mensaje aquí...',
                        'validations' => [
                            'max_elements' => 500
                        ]
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1,
        ]);

        $this->command->info("Formulario de prueba creado con slug: {$form->slug}");
        $this->command->info("URL pública: " . route('public.forms.slug.show', ['id' => $form->id, 'slug' => $form->slug]));
    }
}