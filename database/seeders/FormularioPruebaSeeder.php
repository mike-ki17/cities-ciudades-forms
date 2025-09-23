<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Event;

class FormularioPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar un evento existente o crear uno de prueba
        $event = Event::first();
        
        if (!$event) {
            $event = Event::create([
                'name' => 'Evento de Prueba',
                'city' => 'Ciudad de Prueba',
                'year' => 2024,
                'is_active' => true,
            ]);
        }

        // Crear formulario de prueba con campos condicionales y validaciones
        $formularioPrueba = Form::create([
            'event_id' => $event->id,
            'name' => 'Formulario de Prueba - Campos Condicionales y Validaciones',
            'description' => 'Formulario de ejemplo para probar campos condicionales, validaciones de longitud y otros tipos de validaciones avanzadas.',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'tipo_registro',
                        'label' => 'Tipo de Registro',
                        'type' => 'select',
                        'required' => true,
                        'placeholder' => 'Selecciona el tipo de registro',
                        'options' => [
                            ['value' => 'basico', 'label' => 'Registro Básico'],
                            ['value' => 'premium', 'label' => 'Registro Premium'],
                            ['value' => 'empresarial', 'label' => 'Registro Empresarial']
                        ],
                        'help' => 'El tipo de registro determinará qué campos adicionales se mostrarán'
                    ],
                    [
                        'key' => 'nombre',
                        'label' => 'Nombre Completo',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Ingresa tu nombre completo',
                        'validations' => [
                            'min_length' => 2,
                            'max_length' => 50,
                            'allowed_chars' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz '
                        ],
                        'help' => 'Mínimo 2 caracteres, máximo 50 caracteres. Solo letras y espacios.'
                    ],
                    [
                        'key' => 'email',
                        'label' => 'Correo Electrónico',
                        'type' => 'email',
                        'required' => true,
                        'placeholder' => 'ejemplo@correo.com',
                        'validations' => [
                            'format' => 'email',
                            'unique' => true
                        ],
                        'help' => 'Debe ser un email válido y único'
                    ],
                    [
                        'key' => 'telefono',
                        'label' => 'Teléfono',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '+34 123 456 789',
                        'validations' => [
                            'format' => 'phone',
                            'pattern' => '^[+]?[0-9]{9,15}$',
                            'min_length' => 9,
                            'max_length' => 15
                        ],
                        'help' => 'Formato internacional. Entre 9 y 15 dígitos.'
                    ],
                    [
                        'key' => 'edad',
                        'label' => 'Edad',
                        'type' => 'number',
                        'required' => true,
                        'placeholder' => 'Ingresa tu edad',
                        'validations' => [
                            'min_value' => 18,
                            'max_value' => 100,
                            'step' => 1
                        ],
                        'help' => 'Debes tener entre 18 y 100 años'
                    ],
                    [
                        'key' => 'fecha_nacimiento',
                        'label' => 'Fecha de Nacimiento',
                        'type' => 'date',
                        'required' => false,
                        'validations' => [
                            'min_age' => 18,
                            'max_age' => 65
                        ],
                        'help' => 'Debes tener entre 18 y 65 años'
                    ],
                    [
                        'key' => 'tiene_empresa',
                        'label' => '¿Tienes una empresa?',
                        'type' => 'select',
                        'required' => false,
                        'options' => [
                            ['value' => 'si', 'label' => 'Sí'],
                            ['value' => 'no', 'label' => 'No']
                        ],
                        'help' => 'Esta respuesta mostrará campos adicionales si seleccionas "Sí"'
                    ],
                    // Campos condicionales que aparecen si tiene empresa
                    [
                        'key' => 'nombre_empresa',
                        'label' => 'Nombre de la Empresa',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'Nombre de tu empresa',
                        'validations' => [
                            'min_length' => 3,
                            'max_length' => 100
                        ],
                        'visible' => [
                            'model' => 'tiene_empresa',
                            'condition' => 'equal',
                            'value' => 'si'
                        ],
                        'help' => 'Mínimo 3 caracteres, máximo 100 caracteres'
                    ],
                    [
                        'key' => 'cif_empresa',
                        'label' => 'CIF de la Empresa',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'A12345678',
                        'validations' => [
                            'pattern' => '^[A-Z][0-9]{8}$',
                            'min_length' => 9,
                            'max_length' => 9
                        ],
                        'visible' => [
                            'model' => 'tiene_empresa',
                            'condition' => 'equal',
                            'value' => 'si'
                        ],
                        'help' => 'Formato: 1 letra seguida de 8 dígitos'
                    ],
                    [
                        'key' => 'descripcion_empresa',
                        'label' => 'Descripción de la Empresa',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Describe brevemente tu empresa...',
                        'validations' => [
                            'min_words' => 10,
                            'max_words' => 200,
                            'min_length' => 50,
                            'max_length' => 1000
                        ],
                        'visible' => [
                            'model' => 'tiene_empresa',
                            'condition' => 'equal',
                            'value' => 'si'
                        ],
                        'help' => 'Entre 10 y 200 palabras, mínimo 50 caracteres, máximo 1000 caracteres'
                    ],
                    // Campos condicionales para registro premium
                    [
                        'key' => 'telefono_emergencia',
                        'label' => 'Teléfono de Emergencia',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '+34 987 654 321',
                        'validations' => [
                            'format' => 'phone',
                            'pattern' => '^[+]?[0-9]{9,15}$',
                            'min_length' => 9,
                            'max_length' => 15
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'equal',
                            'value' => 'premium'
                        ],
                        'help' => 'Requerido para registros premium'
                    ],
                    [
                        'key' => 'direccion',
                        'label' => 'Dirección Completa',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Calle, número, ciudad, código postal...',
                        'validations' => [
                            'min_length' => 20,
                            'max_length' => 200,
                            'min_words' => 5,
                            'max_words' => 50
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'in',
                            'value' => ['premium', 'empresarial']
                        ],
                        'help' => 'Mínimo 20 caracteres, máximo 200 caracteres. Entre 5 y 50 palabras.'
                    ],
                    [
                        'key' => 'codigo_postal',
                        'label' => 'Código Postal',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '12345',
                        'validations' => [
                            'format' => 'postal_code',
                            'pattern' => '^[0-9]{5}$',
                            'min_length' => 5,
                            'max_length' => 5
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'in',
                            'value' => ['premium', 'empresarial']
                        ],
                        'help' => '5 dígitos numéricos'
                    ],
                    // Campos condicionales para registro empresarial
                    [
                        'key' => 'numero_empleados',
                        'label' => 'Número de Empleados',
                        'type' => 'number',
                        'required' => false,
                        'placeholder' => 'Número de empleados',
                        'validations' => [
                            'min_value' => 1,
                            'max_value' => 10000,
                            'step' => 1
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'equal',
                            'value' => 'empresarial'
                        ],
                        'help' => 'Entre 1 y 10,000 empleados'
                    ],
                    [
                        'key' => 'sector_empresarial',
                        'label' => 'Sector Empresarial',
                        'type' => 'select',
                        'required' => false,
                        'options' => [
                            ['value' => 'tecnologia', 'label' => 'Tecnología'],
                            ['value' => 'salud', 'label' => 'Salud'],
                            ['value' => 'educacion', 'label' => 'Educación'],
                            ['value' => 'finanzas', 'label' => 'Finanzas'],
                            ['value' => 'retail', 'label' => 'Retail'],
                            ['value' => 'manufactura', 'label' => 'Manufactura'],
                            ['value' => 'servicios', 'label' => 'Servicios'],
                            ['value' => 'otro', 'label' => 'Otro']
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'equal',
                            'value' => 'empresarial'
                        ],
                        'help' => 'Selecciona el sector de tu empresa'
                    ],
                    [
                        'key' => 'sitio_web',
                        'label' => 'Sitio Web de la Empresa',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'https://www.empresa.com',
                        'validations' => [
                            'format' => 'url',
                            'min_length' => 10,
                            'max_length' => 100
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'equal',
                            'value' => 'empresarial'
                        ],
                        'help' => 'URL válida de tu sitio web'
                    ],
                    // Campos de selección múltiple
                    [
                        'key' => 'intereses',
                        'label' => 'Áreas de Interés',
                        'type' => 'select',
                        'required' => false,
                        'validations' => [
                            'min_selections' => 1,
                            'max_selections' => 5
                        ],
                        'options' => [
                            ['value' => 'tecnologia', 'label' => 'Tecnología'],
                            ['value' => 'marketing', 'label' => 'Marketing'],
                            ['value' => 'ventas', 'label' => 'Ventas'],
                            ['value' => 'recursos_humanos', 'label' => 'Recursos Humanos'],
                            ['value' => 'finanzas', 'label' => 'Finanzas'],
                            ['value' => 'operaciones', 'label' => 'Operaciones'],
                            ['value' => 'investigacion', 'label' => 'Investigación'],
                            ['value' => 'desarrollo', 'label' => 'Desarrollo']
                        ],
                        'help' => 'Selecciona entre 1 y 5 áreas de interés'
                    ],
                    // Campo de comentarios con validaciones de palabras
                    [
                        'key' => 'comentarios',
                        'label' => 'Comentarios Adicionales',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Escribe aquí cualquier comentario adicional...',
                        'validations' => [
                            'min_words' => 5,
                            'max_words' => 100,
                            'min_length' => 25,
                            'max_length' => 500
                        ],
                        'help' => 'Entre 5 y 100 palabras, mínimo 25 caracteres, máximo 500 caracteres'
                    ],
                    // Campo de presupuesto con validaciones numéricas
                    [
                        'key' => 'presupuesto',
                        'label' => 'Presupuesto Disponible (€)',
                        'type' => 'number',
                        'required' => false,
                        'placeholder' => '1000',
                        'validations' => [
                            'format' => 'currency',
                            'decimal_places' => 2,
                            'min_value' => 100,
                            'max_value' => 100000,
                            'step' => 0.01
                        ],
                        'visible' => [
                            'model' => 'tipo_registro',
                            'condition' => 'in',
                            'value' => ['premium', 'empresarial']
                        ],
                        'help' => 'Entre €100 y €100,000. Máximo 2 decimales.'
                    ],
                    // Checkbox de términos y condiciones
                    [
                        'key' => 'acepta_terminos',
                        'label' => 'Acepto los términos y condiciones',
                        'type' => 'checkbox',
                        'required' => true,
                        'help' => 'Debes aceptar los términos para continuar'
                    ],
                    [
                        'key' => 'acepta_marketing',
                        'label' => 'Acepto recibir información comercial',
                        'type' => 'checkbox',
                        'required' => false,
                        'help' => 'Opcional: Recibirás ofertas y novedades'
                    ]
                ]
            ],
            'style_json' => [
                'primary_color' => '#00ffbd',
                'background_color' => '#1a1a1a',
                'border_radius' => '8px',
                'form_shadow' => true,
                'header_image_1' => '',
                'header_image_2' => '',
                'background_texture' => ''
            ],
            'is_active' => true,
            'version' => 1
        ]);

        $this->command->info('Formulario de prueba creado exitosamente!');
        $this->command->info('ID del formulario: ' . $formularioPrueba->id);
        $this->command->info('Nombre: ' . $formularioPrueba->name);
        $this->command->info('Evento: ' . $event->full_name);
    }
}
