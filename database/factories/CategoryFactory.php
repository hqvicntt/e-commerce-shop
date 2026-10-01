<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random word for category name (e.g., "Electronics")
        $name = $this->faker->unique()->word();
        return [
            'name' => ucfirst($name), // Capitalize the first letter (e.g., "Electronics")
            'slug' => Str::slug($name), // Convert to URL-friendly slug (e.g., "electronics")
        ];
    }
}
