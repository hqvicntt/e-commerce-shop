<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmedMail;

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

        foreach ($cart as $productId => $item) {
            // Check if the product exists and has sufficient quantity in stock 
            $product = Product::find($productId);

            if (!$product || $product->quantity < $item['quantity']) {
                return redirect()->back()->with('error', 'Sorry, ' . $item['name'] . ' is out of stock or does not have enough inventory.');
            }

            // Deduct the purchased quantity from the product's stock
            $product->quantity -= $item['quantity'];
            $product->save();

            $productNamesArray[] = $item['name'] . ' (x' . $item['quantity'] . ')';
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Apply discount if a valid coupon is present in the session
        if (session()->has('coupon')) {
            $discountPercentage = session()->get('coupon')['discount'];
            $discountAmount = ($totalPrice * $discountPercentage) / 100;
            $totalPrice = $totalPrice - $discountAmount; // Set new discounted grand total
        }

        // Convert the array into a clean readable string (e.g., "iPhone (x2), iPad (x1)")
        $productNamesString = implode(', ', $productNamesArray);

        // Create and store the order record into MySQL database
        $order = Order::create([
            'user_id' => Auth::id(), // Get current logged-in user ID
            'product_names' => $productNamesString,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Send order confirmation email to the user
        Mail::to($order->user->email)->send(new OrderConfirmedMail($order));

        // Clear the shopping cart and coupon session after successful purchase
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('products.index')->with('success', 'Order placed successfully! Thank you for shopping with us.');
    }

    /**
     * Apply a discount coupon code to the shopping cart session.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $code = strtoupper($request->coupon_code);

        // Define our valid coupon codes array hardcoded for optimization
        $validCoupons = [
            'SALE10' => 10, // 10% discount
            'E-SHOP20' => 20, // 20% discount
        ];

        // Check if the input code matches our records
        if (!array_key_exists($code, $validCoupons)) {
            return redirect()->back()->with('error', 'Invalid coupon code! Please try another one.');
        }

        // Store the valid discount percentage factor inside user session
        session()->put('coupon', [
            'code' => $code,
            'discount' => $validCoupons[$code]
        ]);

        return redirect()->back()->with('success', 'Coupon code "' . $code . '" applied successfully!');
    }
}
