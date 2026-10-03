<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Route for displaying the product catalog page
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route for displaying a single product detail page using its ID
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
