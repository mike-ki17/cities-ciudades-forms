<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class ConditionalFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a form with conditional fields example
        $conditionalFormSchema = [
            'fields' => [
                [
                    'key' => 'es_tutor',
                    'label' => '¿Es usted un cuidador de una persona con discapacidad?',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Sí', 'label' => 'Sí'],
                        ['value' => 'No', 'label' => 'No']
                    ],
                    'selectOptions' => [
                        'noneSelectedText' => 'Seleccione'
                    ]
                ],
                [
                    'key' => 'nombre_discapacitado',
                    'label' => 'Nombres completos de la persona que cuida:',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Nombres completos',
                    'visible' => [
                        'model' => 'es_tutor',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'edad_discapacitado',
                    'label' => 'Edad de la persona que cuida:',
                    'type' => 'number',
                    'required' => true,
                    'placeholder' => 'Edad',
                    'validations' => [
                        'min_value' => 0,
                        'max_value' => 120
                    ],
                    'visible' => [
                        'model' => 'es_tutor',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'tipo_discapacidad',
                    'label' => 'Tipo de discapacidad:',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Física', 'label' => 'Física'],
                        ['value' => 'Intelectual', 'label' => 'Intelectual'],
                        ['value' => 'Sensorial', 'label' => 'Sensorial'],
                        ['value' => 'Múltiple', 'label' => 'Múltiple'],
                        ['value' => 'Otra', 'label' => 'Otra']
                    ],
                    'visible' => [
                        'model' => 'es_tutor',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'otra_discapacidad',
                    'label' => 'Especifique otro tipo de discapacidad:',
                    'type' => 'text',
                    'required' => true,
                    'placeholder' => 'Especifique',
                    'visible' => [
                        'model' => 'tipo_discapacidad',
                        'value' => 'Otra',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'tiempo_cuidado',
                    'label' => '¿Cuánto tiempo lleva cuidando a esta persona?',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Menos de 1 año', 'label' => 'Menos de 1 año'],
                        ['value' => '1-3 años', 'label' => '1-3 años'],
                        ['value' => '3-5 años', 'label' => '3-5 años'],
                        ['value' => 'Más de 5 años', 'label' => 'Más de 5 años']
                    ],
                    'visible' => [
                        'model' => 'es_tutor',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'nivel_estudios',
                    'label' => 'Nivel de estudios:',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Primaria', 'label' => 'Primaria'],
                        ['value' => 'Secundaria', 'label' => 'Secundaria'],
                        ['value' => 'Técnico', 'label' => 'Técnico'],
                        ['value' => 'Universitario', 'label' => 'Universitario'],
                        ['value' => 'Postgrado', 'label' => 'Postgrado']
                    ]
                ],
                [
                    'key' => 'tiene_trabajo',
                    'label' => '¿Tiene trabajo actualmente?',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Sí', 'label' => 'Sí'],
                        ['value' => 'No', 'label' => 'No']
                    ]
                ],
                [
                    'key' => 'tipo_trabajo',
                    'label' => 'Tipo de trabajo:',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Tiempo completo', 'label' => 'Tiempo completo'],
                        ['value' => 'Medio tiempo', 'label' => 'Medio tiempo'],
                        ['value' => 'Por horas', 'label' => 'Por horas'],
                        ['value' => 'Freelance', 'label' => 'Freelance']
                    ],
                    'visible' => [
                        'model' => 'tiene_trabajo',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ],
                [
                    'key' => 'ingresos_mensuales',
                    'label' => 'Ingresos mensuales aproximados:',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        ['value' => 'Menos de $500.000', 'label' => 'Menos de $500.000'],
                        ['value' => '$500.000 - $1.000.000', 'label' => '$500.000 - $1.000.000'],
                        ['value' => '$1.000.000 - $2.000.000', 'label' => '$1.000.000 - $2.000.000'],
                        ['value' => 'Más de $2.000.000', 'label' => 'Más de $2.000.000']
                    ],
                    'visible' => [
                        'model' => 'tiene_trabajo',
                        'value' => 'Sí',
                        'condition' => 'equal'
                    ]
                ]
            ]
        ];

        // Create the form
        Form::create([
            'event_id' => null, // General form
            'name' => 'Formulario de Cuidador con Campos Condicionales',
            'description' => 'Formulario de ejemplo que demuestra el uso de campos condicionales para cuidadores de personas con discapacidad.',
            'schema_json' => $conditionalFormSchema,
            'is_active' => true,
            'version' => 1
        ]);

        $this->command->info('Formulario con campos condicionales creado exitosamente.');
    }
}
