<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default Admin account for testing
        User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@eshop.com',
            'password' => bcrypt('admin123'), // Secure password helper
            'role' => 'admin', // Explicitly assign the admin role
        ]);

        // Create 5 categories, each category will automatically have 10 products
        Category::factory(5)
            ->has(Product::factory()->count(10))
            ->create();
    }
}
