<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
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
            'category_id' => $this->faker->numberBetween(1,2),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2,10000, 100000),
            'image' => $this->faker->imageUrl(640, 480, 'food', true),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
