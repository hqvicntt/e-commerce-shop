<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import the Product model to query database
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Import View class for return type

class ProductController extends Controller
{
    /**
     * Display a listing of products with search and filter capabilities.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Fetch all categories to populate the filter dropdown sidebar/topbar
        $categories = Category::all();

        // Initialize the dynamic query builder for Product model with its relationship
        $query = Product::with('category')->where('is_active', true);

        // Apply keyword search filter if present in request query string
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        // Apply category filter if present in request query string
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Execute the query and fetch the filtered list with pagination (12 items per page)
        $products = $query->latest()->paginate(12)->withQueryString();

        // Pass the products data to the view named 'products.index'
        return view('products.index', compact('products', 'categories'));
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

    /**
     * Display the authenticated user's order history.
     * 
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function orderHistory()
    {
        // 1. Enforce security: user must be logged in to view their history
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to view your order history.');
        }

        // 2. Fetch only the orders belonging to the currently logged-in user
        $orders = Order::where('user_id', Auth::id())->latest()->get();

        return view('products.orders', compact('orders'));
    }
}
