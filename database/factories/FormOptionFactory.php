<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormOption>
 */
class FormOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\FormCategory::factory(),
            'value' => $this->faker->slug(1),
            'label' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(95), // 95% chance of being active
        ];
    }
}
