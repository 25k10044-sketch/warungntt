<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'story' => fake()->paragraph(),
            'origin_region' => fake()->randomElement(['Kupang', 'Timor', 'Flores', 'Sumba']),
            'main_ingredients' => fake()->words(4, true),
            'price' => fake()->numberBetween(10000, 150000),
            'image' => null,
            'is_available' => true,
        ];
    }
}
