<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(ProductFactory::class)]
class Product extends Model
{
    use HasFactory;

    /**
     * Get the category that owns the product.
     * 
     * @return BelongsTo
     */
    public function category(): BelongsTo // Use the singular form for the name because the product belongs to only one category
    {
        return $this->belongsTo(Category::class); // Sản phẩm thuộc về danh mục
    }
}
