<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => \App\Models\Event::factory(),
            'name' => $this->faker->words(3, true) . ' Formulario',
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'version' => $this->faker->numberBetween(1, 5),
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'nombre',
                        'type' => 'text',
                        'label' => 'Nombre Completo',
                        'required' => true
                    ],
                    [
                        'id' => 'email',
                        'type' => 'email',
                        'label' => 'Correo Electrónico',
                        'required' => true
                    ]
                ]
            ],
            'style_json' => [
                'theme' => 'default',
                'colors' => [
                    'primary' => '#007bff',
                    'secondary' => '#6c757d'
                ]
            ]
        ];
    }
}
