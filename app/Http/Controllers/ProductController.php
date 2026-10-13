<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import the Product model to query database
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Import View class for return type
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products with search, filter, average ratings, and special homepage sections.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Fetch all categories to populate the filter dropdown sidebar/topbar
        $categories = Category::all();

        // Fetch exactly 6 newest items for the "Latest Arrivals" carousel section
        $latestProducts = Product::where('is_active', true)
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();

        // Initialize the dynamic query builder for Product model with its relationships and average rating calculation (Main query handler for the paginated product catalog filter list)
        $query = Product::with('category')
            ->withAvg('reviews', 'rating') // Auto-generates 'reviews_avg_rating' column
            ->where('is_active', true);

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
        return view('products.index', compact('products', 'categories', 'latestProducts'));
    }

    /**
     * Display the specified product detail with its reviews and average rating.
     * 
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        // Eager load reviews and the users who wrote them to prevent N+1 query issue
        $product = Product::with(['reviews.user', 'category'])
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

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

    /**
     * Store a newly created product review in the database.
     * 
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function storeReview(Request $request, int $id): RedirectResponse
    {
        // 1. Enforce security: user must be logged in to leave a review
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to write a review.');
        }

        // 2. Validate the incoming rating and comment parameters
        $request->validate([
            'rating' => 'required|integer|between:1,5', // Must be an integer from 1 to 5
            'comment' => 'required|string|min:5', // Minimum 5 characters
        ]);

        // 3. Create the review record linked to current user and product
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Thank you! Your product review has been submitted.');
    }
}
