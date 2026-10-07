<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display the shopping cart items.
     * 
     * @return View
     */
    public function index(): View
    {
        // Get cart items from session, default to an empty array if cart doesn't exist
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the shopping cart.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function add(int $id): RedirectResponse
    {
        // Find the product or fail with 404
        $product = Product::findOrFail($id);

        // Get current cart from session
        $cart = session()->get('cart', []);

        // If product already exists in cart, increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // If product doesn't exist, add it to cart with quantity 1
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        // Put the updated cart back into the session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Process the order checkout logic.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function checkout(Request $request): RedirectResponse
    {
        // Enforce security: user must be logged in to checkout
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to place an order.');
        }

        // Fetch the cart item array from session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        // Compile product names and compute the grand total price
        $productNamesArray = [];
        $totalPrice = 0;

        foreach ($cart as $item) {
            $productNamesArray[] = $item['name'] . ' (x' . $item['quantity'] . ')';
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Convert the array into a clean readable string (e.g., "iPhone (x2), iPad (x1)")
        $productNamesString = implode(', ', $productNamesArray);

        // Create and store the order record into MySQL database
        Order::create([
            'user_id' => Auth::id(), // Get current logged-in user ID
            'product_names' => $productNamesString,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Clear the shopping cart session after successful purchase
        session()->forget('cart');

        return redirect()->route('products.index')->with('success', 'Order placed successfully! Thank you for shopping with us.');
    }
}
