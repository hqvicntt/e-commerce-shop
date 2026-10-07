<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with all products.
     * 
     * @return View
     */
    public function dashboard(): View
    {
        // Fetch all products with their related categories for admin view
        $products = Product::with('category')->latest()->get();

        return view('admin.dashboard', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     * 
     * @return View
     */
    public function create(): View
    {
        // Fetch all categories to populate the dropdown select menu
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in the database.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming product form data
        $request->validate([
            'category_id' => 'required|exists:categories,id', // Must exist in categories table
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Create the new product record in the database
        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Automatically generate slug from name
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'is_active' => true, // Default to true
        ]);

        // Redirect back to admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     * 
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        // Find the product by its ID or throw a 404 error
        $product = Product::findOrFail($id);

        // Fetch all categories to populate the dropdown select menu
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in the database.
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // 1. Find the existing product or fail with 404
        $product = Product::findOrFail($id);

        // 2. Validate the incoming updated product form data
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // 3. Update the product record with new data using mass assignment
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Re-generate slug if name changed
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        // 4. Redirect back to admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from the database.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // 1. Find the product or fail with 404
        $product = Product::findOrFail($id);

        // 2. Delete the product record from MySQL database
        $product->delete();

        // 3. Redirect back to admin dashboard with a success message
        return redirect()->route('admin.dashboard')->with('success', 'Product deleted successfully!');
    }

    /**
     * Display a listing of all customer orders.
     * 
     * @return View
     */
    public function orders(): View
    {
        // Fetch all orders with their related users to prevent N+1 query issues
        $orders = Order::with('user')->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }
}
