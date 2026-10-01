<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key linking to categories table
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable(); // Product image path
            $table->text('description')->nullable(); // Product detailed description
            $table->decimal('price', 15, 2); // Product price
            $table->integer('quantity')->default(0); // Stock quantity
            $table->boolean('is_active')->default(true); // Status: active or inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
