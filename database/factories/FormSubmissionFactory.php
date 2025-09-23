<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormSubmission>
 */
class FormSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'form_id' => \App\Models\Form::factory(),
            'participant_id' => \App\Models\Participant::factory(),
            'data_json' => [
                'nombre' => $this->faker->name(),
                'email' => $this->faker->email(),
                'telefono' => $this->faker->phoneNumber(),
                'comentarios' => $this->faker->sentence(),
            ],
            'submitted_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
