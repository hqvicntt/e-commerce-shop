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
        // Validate the form input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Must match password_confirmation field
        ]);

        // Create the new user record in the database and assign it to a variable 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt the password for security
            'role' => 'user', // Default role is 'user'
        ]);

        // Automatically log in the newly created user session
        Auth::login($user);

        // Redirect to login page with a success message
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

    /**
     * Show the form for editing the authenticated user's profile.
     * 
     * @return View
     */
    public function editProfile(): View
    {
        // Fetch the currently logged-in user instance directly from Auth facade
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    /**
     * Update the authenticated user's profile parameters.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Validate the profile modification inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed', // Must match with new_password_confirmation
        ]);

        // Check and verify current password match if user intends to update to a new password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
            }
            // Encrypt and assign the safe new password string buffer
            $user->password = Hash::make($request->new_password);
        }

        // Update the basic account specifications data fields
        $user->name = $request->name;
        $user->save();

        // Redirect back to store front with a success toast notification
        return redirect()->route('products.index')->with('success', 'Your personal account profile details updated successfully!');
    }
}
