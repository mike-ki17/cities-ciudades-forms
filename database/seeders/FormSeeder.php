<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $generalCity = City::where('name', 'General')->first();
        $bogotaCity = City::where('name', 'Bogotá')->first();

        if ($generalCity) {
            Form::firstOrCreate(
                ['city_id' => $generalCity->id, 'version' => 1],
                [
                    'title' => 'Formulario General de Participación',
                    'description' => 'Formulario general para todas las ciudades',
                    'schema_json' => [
                        'fields' => [
                            [
                                'key' => 'nombre',
                                'label' => 'Nombre Completo',
                                'type' => 'text',
                                'required' => true,
                                'validations' => ['min' => 2, 'max' => 100],
                                'placeholder' => 'Ingrese su nombre completo',
                                'order' => 1
                            ],
                            [
                                'key' => 'email',
                                'label' => 'Correo Electrónico',
                                'type' => 'email',
                                'required' => true,
                                'validations' => ['max' => 255],
                                'placeholder' => 'ejemplo@correo.com',
                                'order' => 2
                            ],
                            [
                                'key' => 'telefono',
                                'label' => 'Teléfono',
                                'type' => 'text',
                                'required' => false,
                                'validations' => ['max' => 20],
                                'placeholder' => '+57 300 123 4567',
                                'order' => 3
                            ],
                            [
                                'key' => 'document_number',
                                'label' => 'Número de Documento',
                                'type' => 'number',
                                'required' => true,
                                'validations' => [
                                    'max_elements' => 12
                                ],
                                'placeholder' => 'Solo números, sin espacios, letras, puntos o símbolos',
                                'order' => 4
                            ],
                            [
                                'key' => 'edad',
                                'label' => 'Edad',
                                'type' => 'number',
                                'required' => true,
                                'validations' => ['min' => 18, 'max' => 100],
                                'order' => 5
                            ],
                            [
                                'key' => 'intereses',
                                'label' => 'Áreas de Interés',
                                'type' => 'select',
                                'required' => true,
                                'options' => [
                                    ['value' => 'tecnologia', 'label' => 'Tecnología'],
                                    ['value' => 'educacion', 'label' => 'Educación'],
                                    ['value' => 'salud', 'label' => 'Salud'],
                                    ['value' => 'medio_ambiente', 'label' => 'Medio Ambiente'],
                                    ['value' => 'cultura', 'label' => 'Cultura'],
                                ],
                                'order' => 6
                            ],
                            [
                                'key' => 'comentarios',
                                'label' => 'Comentarios Adicionales',
                                'type' => 'textarea',
                                'required' => false,
                                'validations' => ['max' => 500],
                                'placeholder' => 'Escriba aquí sus comentarios...',
                                'order' => 7
                            ],
                            [
                                'key' => 'acepta_terminos',
                                'label' => 'Acepto los términos y condiciones',
                                'type' => 'checkbox',
                                'required' => true,
                                'order' => 8
                            ]
                        ]
                    ],
                    'is_active' => true,
                ]
            );
        }

        if ($bogotaCity) {
            Form::firstOrCreate(
                ['city_id' => $bogotaCity->id, 'version' => 1],
                [
                    'title' => 'Formulario Específico de Bogotá',
                    'description' => 'Formulario específico para participantes de Bogotá',
                    'schema_json' => [
                        'fields' => [
                            [
                                'key' => 'nombre',
                                'label' => 'Nombre Completo',
                                'type' => 'text',
                                'required' => true,
                                'validations' => ['min' => 2, 'max' => 100],
                                'placeholder' => 'Ingrese su nombre completo',
                                'order' => 1
                            ],
                            [
                                'key' => 'email',
                                'label' => 'Correo Electrónico',
                                'type' => 'email',
                                'required' => true,
                                'validations' => ['max' => 255],
                                'placeholder' => 'ejemplo@correo.com',
                                'order' => 2
                            ],
                            [
                                'key' => 'localidad',
                                'label' => 'Localidad de Bogotá',
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
                                    ['value' => 'los_martires', 'label' => 'Los Mártires'],
                                    ['value' => 'antonio_narino', 'label' => 'Antonio Nariño'],
                                    ['value' => 'puente_aranda', 'label' => 'Puente Aranda'],
                                    ['value' => 'candelaria', 'label' => 'La Candelaria'],
                                    ['value' => 'rafael_uribe', 'label' => 'Rafael Uribe Uribe'],
                                    ['value' => 'ciudad_bolivar', 'label' => 'Ciudad Bolívar'],
                                    ['value' => 'sumapaz', 'label' => 'Sumapaz'],
                                ],
                                'order' => 3
                            ],
                            [
                                'key' => 'proyecto_interes',
                                'label' => 'Proyecto de Interés',
                                'type' => 'select',
                                'required' => true,
                                'options' => [
                                    ['value' => 'movilidad', 'label' => 'Movilidad Sostenible'],
                                    ['value' => 'espacio_publico', 'label' => 'Espacio Público'],
                                    ['value' => 'cultura', 'label' => 'Cultura Ciudadana'],
                                    ['value' => 'medio_ambiente', 'label' => 'Medio Ambiente'],
                                ],
                                'order' => 4
                            ],
                            [
                                'key' => 'comentarios',
                                'label' => 'Comentarios sobre Bogotá',
                                'type' => 'textarea',
                                'required' => false,
                                'validations' => ['max' => 500],
                                'placeholder' => 'Comparta sus ideas para mejorar Bogotá...',
                                'order' => 5
                            ]
                        ]
                    ],
                    'is_active' => true,
                ]
            );
        }
    }
}