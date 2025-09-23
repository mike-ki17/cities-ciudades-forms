<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\Event;
use Illuminate\Support\Str;

class FormularioValidacionesCompletasSeeder extends Seeder
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
                'name' => 'Evento de Prueba - Validaciones',
                'city' => 'Bogotá',
                'year' => 2025,
                'description' => 'Evento para probar todas las validaciones del sistema',
                'is_active' => true,
            ]);
        }

        // Crear el formulario con todas las validaciones
        $form = Form::create([
            'event_id' => $event->id,
            'name' => 'Formulario Completo - Todas las Validaciones',
            'slug' => 'formulario-validaciones-completas-' . time(),
            'description' => 'Formulario completo para probar todas las validaciones implementadas en el sistema. Incluye campos de texto, números, fechas, archivos, condicionales y más.',
            'schema_json' => [
                'fields' => [
                    // 1. VALIDACIONES DE TEXTO
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
                        'description' => 'Debe tener entre 2 y 100 caracteres'
                    ],
                    [
                        'key' => 'descripcion_personal',
                        'label' => 'Descripción Personal',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Cuéntanos sobre ti...',
                        'validations' => [
                            'min_words' => 5,
                            'max_words' => 200
                        ],
                        'description' => 'Debe tener entre 5 y 200 palabras'
                    ],
                    [
                        'key' => 'codigo_usuario',
                        'label' => 'Código de Usuario',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'ABC123',
                        'validations' => [
                            'allowed_chars' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
                            'forbidden_chars' => '!@#$%^&*()'
                        ],
                        'description' => 'Solo letras mayúsculas y números'
                    ],
                    [
                        'key' => 'telefono',
                        'label' => 'Teléfono',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => '+57 300 123 4567',
                        'validations' => [
                            'pattern' => '^\\+?[1-9]\\d{1,14}$'
                        ],
                        'description' => 'Formato internacional válido'
                    ],

                    // 2. VALIDACIONES DE EMAIL
                    [
                        'key' => 'email_principal',
                        'label' => 'Email Principal',
                        'type' => 'email',
                        'required' => true,
                        'placeholder' => 'usuario@ejemplo.com',
                        'validations' => [
                            'format' => 'email'
                        ],
                        'description' => 'Email válido requerido'
                    ],
                    [
                        'key' => 'email_secundario',
                        'label' => 'Email Secundario',
                        'type' => 'email',
                        'required' => false,
                        'placeholder' => 'backup@ejemplo.com',
                        'validations' => [
                            'format' => 'email'
                        ],
                        'description' => 'Email opcional'
                    ],

                    // 3. VALIDACIONES NUMÉRICAS
                    [
                        'key' => 'edad',
                        'label' => 'Edad',
                        'type' => 'number',
                        'required' => true,
                        'placeholder' => '25',
                        'validations' => [
                            'min' => 18,
                            'max' => 100
                        ],
                        'description' => 'Entre 18 y 100 años'
                    ],
                    [
                        'key' => 'salario',
                        'label' => 'Salario Mensual',
                        'type' => 'number',
                        'required' => false,
                        'placeholder' => '2500000',
                        'validations' => [
                            'min' => 0,
                            'max' => 50000000,
                            'decimal_places' => 0
                        ],
                        'description' => 'Sin decimales, máximo 50 millones'
                    ],
                    [
                        'key' => 'porcentaje_descuento',
                        'label' => 'Porcentaje de Descuento',
                        'type' => 'number',
                        'required' => false,
                        'placeholder' => '15.5',
                        'validations' => [
                            'min' => 0,
                            'max' => 100,
                            'decimal_places' => 2,
                            'step' => 0.1
                        ],
                        'description' => 'Entre 0 y 100%, máximo 2 decimales'
                    ],

                    // 4. VALIDACIONES DE FECHA
                    [
                        'key' => 'fecha_nacimiento',
                        'label' => 'Fecha de Nacimiento',
                        'type' => 'date',
                        'required' => true,
                        'validations' => [
                            'min_date' => '1900-01-01',
                            'max_date' => '2010-12-31'
                        ],
                        'description' => 'Entre 1900 y 2010'
                    ],
                    [
                        'key' => 'fecha_ingreso',
                        'label' => 'Fecha de Ingreso',
                        'type' => 'date',
                        'required' => false,
                        'validations' => [
                            'min_date' => '2020-01-01',
                            'max_date' => '2025-12-31'
                        ],
                        'description' => 'Entre 2020 y 2025'
                    ],

                    // 5. VALIDACIONES DE ARCHIVO
                    [
                        'key' => 'foto_perfil',
                        'label' => 'Foto de Perfil',
                        'type' => 'file',
                        'required' => false,
                        'validations' => [
                            'file_types' => ['image/jpeg', 'image/png', 'image/gif'],
                            'max_file_size' => 5242880 // 5MB en bytes
                        ],
                        'description' => 'Solo imágenes JPG, PNG o GIF, máximo 5MB'
                    ],
                    [
                        'key' => 'documento_pdf',
                        'label' => 'Documento PDF',
                        'type' => 'file',
                        'required' => false,
                        'validations' => [
                            'file_types' => ['application/pdf'],
                            'max_file_size' => 10485760 // 10MB en bytes
                        ],
                        'description' => 'Solo archivos PDF, máximo 10MB'
                    ],

                    // 6. VALIDACIONES CONDICIONALES
                    [
                        'key' => 'tiene_empresa',
                        'label' => '¿Tiene Empresa?',
                        'type' => 'radio',
                        'required' => true,
                        'options' => [
                            ['value' => 'si', 'label' => 'Sí'],
                            ['value' => 'no', 'label' => 'No']
                        ],
                        'description' => 'Seleccione si tiene empresa'
                    ],
                    [
                        'key' => 'nombre_empresa',
                        'label' => 'Nombre de la Empresa',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => 'Nombre de su empresa',
                        'validations' => [
                            'min_length' => 2,
                            'max_length' => 100
                        ],
                        'visible' => [
                            'field' => 'tiene_empresa',
                            'operator' => 'equals',
                            'value' => 'si'
                        ],
                        'description' => 'Solo visible si tiene empresa'
                    ],
                    [
                        'key' => 'nit_empresa',
                        'label' => 'NIT de la Empresa',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '12345678-9',
                        'validations' => [
                            'pattern' => '^[0-9]{8,9}-[0-9]$'
                        ],
                        'visible' => [
                            'field' => 'tiene_empresa',
                            'operator' => 'equals',
                            'value' => 'si'
                        ],
                        'description' => 'Formato: 12345678-9'
                    ],

                    // 7. VALIDACIONES DE SELECCIÓN
                    [
                        'key' => 'pais',
                        'label' => 'País',
                        'type' => 'select',
                        'required' => true,
                        'options' => [
                            ['value' => 'colombia', 'label' => 'Colombia'],
                            ['value' => 'mexico', 'label' => 'México'],
                            ['value' => 'argentina', 'label' => 'Argentina'],
                            ['value' => 'chile', 'label' => 'Chile'],
                            ['value' => 'peru', 'label' => 'Perú']
                        ],
                        'description' => 'Seleccione su país'
                    ],
                    [
                        'key' => 'intereses',
                        'label' => 'Intereses',
                        'type' => 'checkbox',
                        'required' => false,
                        'options' => [
                            ['value' => 'tecnologia', 'label' => 'Tecnología'],
                            ['value' => 'deportes', 'label' => 'Deportes'],
                            ['value' => 'musica', 'label' => 'Música'],
                            ['value' => 'viajes', 'label' => 'Viajes'],
                            ['value' => 'lectura', 'label' => 'Lectura']
                        ],
                        'description' => 'Seleccione sus intereses'
                    ],

                    // 8. VALIDACIONES DE URL
                    [
                        'key' => 'sitio_web',
                        'label' => 'Sitio Web',
                        'type' => 'url',
                        'required' => false,
                        'placeholder' => 'https://www.ejemplo.com',
                        'validations' => [
                            'format' => 'url'
                        ],
                        'description' => 'URL válida'
                    ],
                    [
                        'key' => 'linkedin',
                        'label' => 'Perfil de LinkedIn',
                        'type' => 'url',
                        'required' => false,
                        'placeholder' => 'https://linkedin.com/in/usuario',
                        'validations' => [
                            'format' => 'url',
                            'pattern' => '^https://linkedin\\.com/in/'
                        ],
                        'description' => 'URL de LinkedIn válida'
                    ],

                    // 9. VALIDACIONES DE CONTRASEÑA
                    [
                        'key' => 'contrasena',
                        'label' => 'Contraseña',
                        'type' => 'password',
                        'required' => true,
                        'validations' => [
                            'min_length' => 8,
                            'max_length' => 50,
                            'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]'
                        ],
                        'description' => 'Mínimo 8 caracteres, debe incluir mayúscula, minúscula, número y símbolo'
                    ],
                    [
                        'key' => 'confirmar_contrasena',
                        'label' => 'Confirmar Contraseña',
                        'type' => 'password',
                        'required' => true,
                        'validations' => [
                            'matches' => 'contrasena'
                        ],
                        'description' => 'Debe coincidir con la contraseña'
                    ],

                    // 10. VALIDACIONES DE TELÉFONO
                    [
                        'key' => 'telefono_emergencia',
                        'label' => 'Teléfono de Emergencia',
                        'type' => 'tel',
                        'required' => false,
                        'placeholder' => '+57 300 123 4567',
                        'validations' => [
                            'pattern' => '^\\+?[1-9]\\d{1,14}$'
                        ],
                        'description' => 'Formato internacional válido'
                    ],

                    // 11. VALIDACIONES DE RANGO
                    [
                        'key' => 'nivel_satisfaccion',
                        'label' => 'Nivel de Satisfacción',
                        'type' => 'range',
                        'required' => true,
                        'validations' => [
                            'min' => 1,
                            'max' => 10,
                            'step' => 1
                        ],
                        'description' => 'Del 1 al 10'
                    ],

                    // 12. VALIDACIONES DE COLOR
                    [
                        'key' => 'color_favorito',
                        'label' => 'Color Favorito',
                        'type' => 'color',
                        'required' => false,
                        'validations' => [
                            'format' => 'hex'
                        ],
                        'description' => 'Seleccione su color favorito'
                    ],

                    // 13. VALIDACIONES DE TEXTO CON FORMATO
                    [
                        'key' => 'codigo_postal',
                        'label' => 'Código Postal',
                        'type' => 'text',
                        'required' => false,
                        'placeholder' => '110111',
                        'validations' => [
                            'pattern' => '^[0-9]{6}$',
                            'min_length' => 6,
                            'max_length' => 6
                        ],
                        'description' => '6 dígitos numéricos'
                    ],

                    // 14. VALIDACIONES DE ACEPTACIÓN
                    [
                        'key' => 'acepta_terminos',
                        'label' => 'Acepto los Términos y Condiciones',
                        'type' => 'checkbox',
                        'required' => true,
                        'validations' => [
                            'required' => true
                        ],
                        'description' => 'Debe aceptar los términos'
                    ],
                    [
                        'key' => 'acepta_marketing',
                        'label' => 'Acepto recibir información de marketing',
                        'type' => 'checkbox',
                        'required' => false,
                        'description' => 'Opcional'
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1
        ]);

        $this->command->info("✅ Formulario creado exitosamente:");
        $this->command->info("   📋 Nombre: {$form->name}");
        $this->command->info("   🔗 Slug: {$form->slug}");
        $this->command->info("   📊 Total de campos: " . count($form->schema_json['fields']));
        $this->command->info("   🌐 URL de acceso: http://localhost:8000/form/{$form->slug}");
        $this->command->info("   🎯 URL de administración: http://localhost:8000/admin/forms/{$form->id}");
        
        $this->command->info("\n📋 Campos incluidos:");
        foreach ($form->schema_json['fields'] as $field) {
            $validations = isset($field['validations']) ? count($field['validations']) : 0;
            $this->command->info("   • {$field['label']} ({$field['type']}) - {$validations} validaciones");
        }
        
        $this->command->info("\n🧪 Tipos de validaciones incluidas:");
        $this->command->info("   • Longitud de caracteres (min_length, max_length)");
        $this->command->info("   • Conteo de palabras (min_words, max_words)");
        $this->command->info("   • Caracteres permitidos/prohibidos (allowed_chars, forbidden_chars)");
        $this->command->info("   • Patrones de expresión regular (pattern)");
        $this->command->info("   • Validaciones de formato (email, url, hex)");
        $this->command->info("   • Validaciones numéricas (min, max, decimal_places, step)");
        $this->command->info("   • Validaciones de fecha (min_date, max_date)");
        $this->command->info("   • Validaciones de archivo (file_types, max_file_size)");
        $this->command->info("   • Validaciones condicionales (visible)");
        $this->command->info("   • Validaciones de coincidencia (matches)");
        
        $this->command->info("\n🎉 ¡Formulario listo para probar todas las validaciones!");
    }
}
