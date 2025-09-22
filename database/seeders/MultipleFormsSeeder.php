<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Form;
use Illuminate\Database\Seeder;

class MultipleFormsSeeder extends Seeder
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

        // Create multiple active forms for the same city
        $forms = [
            [
                'name' => 'Formulario de Contacto',
                'description' => 'Formulario para contactar con la administración',
                'schema_json' => [
                    'fields' => [
                        [
                            'key' => 'name',
                            'label' => 'Nombre Completo',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Ingresa tu nombre completo',
                            'validations' => ['max_elements' => 100]
                        ],
                        [
                            'key' => 'email',
                            'label' => 'Correo Electrónico',
                            'type' => 'email',
                            'required' => true,
                            'placeholder' => 'tu@email.com',
                            'validations' => ['max_elements' => 50]
                        ],
                        [
                            'key' => 'subject',
                            'label' => 'Asunto',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Asunto del mensaje',
                            'validations' => ['max_elements' => 100]
                        ],
                        [
                            'key' => 'message',
                            'label' => 'Mensaje',
                            'type' => 'textarea',
                            'required' => true,
                            'placeholder' => 'Escribe tu mensaje aquí...',
                            'validations' => ['max_elements' => 500]
                        ]
                    ]
                ],
                'is_active' => true,
                'version' => 1,
            ],
            [
                'name' => 'Formulario de Sugerencias',
                'description' => 'Formulario para enviar sugerencias y mejoras',
                'schema_json' => [
                    'fields' => [
                        [
                            'key' => 'name',
                            'label' => 'Nombre',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Tu nombre',
                            'validations' => ['max_elements' => 50]
                        ],
                        [
                            'key' => 'email',
                            'label' => 'Email',
                            'type' => 'email',
                            'required' => true,
                            'placeholder' => 'tu@email.com',
                            'validations' => ['max_elements' => 50]
                        ],
                        [
                            'key' => 'category',
                            'label' => 'Categoría',
                            'type' => 'select',
                            'required' => true,
                            'options' => [
                                ['value' => 'mejora', 'label' => 'Mejora'],
                                ['value' => 'bug', 'label' => 'Reporte de Error'],
                                ['value' => 'nueva_funcionalidad', 'label' => 'Nueva Funcionalidad'],
                                ['value' => 'otro', 'label' => 'Otro']
                            ]
                        ],
                        [
                            'key' => 'suggestion',
                            'label' => 'Sugerencia',
                            'type' => 'textarea',
                            'required' => true,
                            'placeholder' => 'Describe tu sugerencia...',
                            'validations' => ['max_elements' => 1000]
                        ]
                    ]
                ],
                'is_active' => true,
                'version' => 1,
            ],
            [
                'name' => 'Formulario de Registro de Eventos',
                'description' => 'Formulario para registrarse en eventos de la ciudad',
                'schema_json' => [
                    'fields' => [
                        [
                            'key' => 'name',
                            'label' => 'Nombre Completo',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Nombre completo',
                            'validations' => ['max_elements' => 100]
                        ],
                        [
                            'key' => 'email',
                            'label' => 'Correo Electrónico',
                            'type' => 'email',
                            'required' => true,
                            'placeholder' => 'tu@email.com',
                            'validations' => ['max_elements' => 50]
                        ],
                        [
                            'key' => 'phone',
                            'label' => 'Teléfono',
                            'type' => 'text',
                            'required' => true,
                            'placeholder' => 'Tu número de teléfono',
                            'validations' => ['max_elements' => 20]
                        ],
                        [
                            'key' => 'event_type',
                            'label' => 'Tipo de Evento',
                            'type' => 'select',
                            'required' => true,
                            'options' => [
                                ['value' => 'cultural', 'label' => 'Cultural'],
                                ['value' => 'deportivo', 'label' => 'Deportivo'],
                                ['value' => 'educativo', 'label' => 'Educativo'],
                                ['value' => 'social', 'label' => 'Social']
                            ]
                        ],
                        [
                            'key' => 'interests',
                            'label' => 'Intereses',
                            'type' => 'checkbox',
                            'required' => false,
                            'options' => [
                                ['value' => 'arte', 'label' => 'Arte'],
                                ['value' => 'musica', 'label' => 'Música'],
                                ['value' => 'deporte', 'label' => 'Deporte'],
                                ['value' => 'tecnologia', 'label' => 'Tecnología'],
                                ['value' => 'medio_ambiente', 'label' => 'Medio Ambiente']
                            ]
                        ]
                    ]
                ],
                'is_active' => true,
                'version' => 1,
            ]
        ];

        foreach ($forms as $formData) {
            $form = Form::create(array_merge($formData, [
                'city_id' => $event->id,
            ]));

            $this->command->info("Formulario '{$form->name}' creado con slug: {$form->slug}");
            $this->command->info("URL pública: " . route('public.forms.slug.show', ['id' => $form->id, 'slug' => $form->slug]));
        }

        $this->command->info("Se crearon " . count($forms) . " formularios activos para el evento: {$event->full_name}");
    }
}