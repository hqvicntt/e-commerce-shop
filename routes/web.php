<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;

// Route for displaying the product catalog page
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route for displaying a single product detail page using its ID
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Route for displaying the registration form
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Route for processing the registration data
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');

// Route for displaying the login form
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Route for processing the login authentication
Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');

// Route for logging out the authenticated user
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route for displaying the shopping cart page
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Route for adding a product to the cart
Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
