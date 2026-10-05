<?php

namespace App\Http\Controllers;

use App\Models\User; // Import User model to insert data
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import Hash class for password encryption
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Import class for page redirection
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     * 
     * @return View
     */
    public function register(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeRegister(Request $request): RedirectResponse
    {
        // 1. Validate the form input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Must match password_confirmation field
        ]);

        // 2. Create and save the new user into MySQL database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt the password for security
        ]);

        // 3. Redirect to login page with a success message
        return redirect()->route('products.index')->with('success', 'Account created successfully!');
    }

    /**
     * Show the login form.
     * 
     * @return View
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication attempt.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeLogin(Request $request): RedirectResponse
    {
        // 1. Validate the incoming request data
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Attempt to log the user in (Laravel automatically checks and decrypts password)
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent Session Fixation attacks
            $request->session()->regenerate();

            // Redirect to products catalog page with success message
            return redirect()->route('products.index')->with('success', 'Welcome back!');
        }

        // 3. If authentication fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // Retain only email input for security
    }

    /**
     * Log the user out of the application.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // 1. Log the user out of the session guard
        Auth::logout();

        // 2. Invalidate the user's session to clear all stored data
        $request->session()->invalidate();

        // 3. Regenerate the CSRF token for security purposes
        $request->session()->regenerateToken();

        // 4. Redirect the user back to the product catalog page
        return redirect()->route('products.index')->with('success', 'Logged out successfully!');
    }
}
