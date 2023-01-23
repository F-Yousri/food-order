<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        fake()->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant(fake()));

        return [
            'name' => fake()->unique()->meatName(),
            'stock' => fake()->numberBetween(100, 50000),
            'recommended_stock' => fake()->numberBetween(10000, 50000),
        ];
    }
}
