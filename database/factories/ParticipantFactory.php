<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'document_type' => $this->faker->randomElement(['CC', 'CE', 'TI', 'RC']),
            'document_number' => $this->faker->unique()->numerify('########'),
            'birth_date' => $this->faker->date('Y-m-d', '2000-01-01'),
        ];
    }
}
