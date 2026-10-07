<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;

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

// Route group protected by 'auth' middleware (Requires authentication (Accessible by both regular users and admins))
Route::middleware(['auth'])->group(function () {
    // Route for processing the checkout request
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// Route group protected by 'auth' and 'admin' middleware (Requires authentication and admin role verification)
Route::middleware(['auth', 'admin'])->group(function () {

    // Admin Dashboard Page
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Show Create Product Form
    Route::get('/admin/products/create', [AdminController::class, 'create'])->name('admin.products.create');

    // Store New Product Process
    Route::post('/admin/products/create', [AdminController::class, 'store'])->name('admin.products.store');

    // Show Edit Product Form
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'edit'])->name('admin.products.edit');

    // Update Product Process
    Route::post('/admin/products/edit/{id}', [AdminController::class, 'update'])->name('admin.products.update');

    // Delete Product Process
    Route::post('/admin/products/delete/{id}', [AdminController::class, 'destroy'])->name('admin.products.destroy');

    // Route for viewing the admin's order list
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
});
