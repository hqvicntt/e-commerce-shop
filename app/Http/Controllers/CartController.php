<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
}
