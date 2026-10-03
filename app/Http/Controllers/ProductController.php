<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import the Product model to query database
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View class for return type

class ProductController extends Controller
{
    /**
     * Display a listing of the active products.
     * 
     * @return View
     */
    public function index(): View
    {
        // Fetch all products where is_active is true, ordered by latest created
        $products = Product::where('is_active', true)
            ->latest()
            ->get();

        // Pass the products data to the view named 'products.index'
        return view('products.index', compact('products'));
    }

    /**
     * Display the specified product.
     * 
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        // Find the product by its ID or throw a 404 error if not found
        $product = Product::findOrFail($id);

        // Pass the single product data to the view named 'products.show'
        return view('products.show', compact('product'));
    }
}
