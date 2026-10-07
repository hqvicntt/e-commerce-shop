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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Relationship linking the order to the user who bought it
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Simple storage for order product details and pricing
            $table->string('product_names'); // e.g., "iPhone 15 (x2), iPad Air (x1)"
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
