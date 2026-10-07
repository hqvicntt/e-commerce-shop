<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
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
}
