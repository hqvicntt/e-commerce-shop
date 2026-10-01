<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3); // Generate a random product name with 3 words
        return [
            // Automatically create a category or pick a random existing category ID
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => null, // Keep image null for now, we will add images later
            'description' => $this->faker->paragraph(), // Generate random description paragraph
            'price' => $this->faker->randomFloat(2, 10, 1000), // Random price between 10.00 and 1000.00
            'quantity' => $this->faker->numberBetween(10, 100), // Random stock quantity between 10 and 100
            'is_active' => true, // Default to true so products are visible
        ];
    }
}
