<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Allow access to login page even if logged in (for switching accounts)
        // The login() method will handle logging out existing user
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Logout any existing user first to prevent session conflicts
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            /** @var User $user */
            $user = Auth::user();
            
            // Check if the request expects JSON (AJAX request)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $user->isAdmin() ? '/admin' : route('home')
                ]);
            }
            
            // Redirect admin to admin panel, customer to home
            if ($user->isAdmin()) {
                return redirect('/admin');
            }
            
            // Clear any intended URL that might point to API endpoints
            $intended = redirect()->intended()->getTargetUrl();
            if (str_contains($intended, '/favorites/list') || str_contains($intended, '/api/')) {
                return redirect()->route('home');
            }
            
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            // Redirect admin to admin panel
            if ($user->isAdmin()) {
                return redirect('/admin');
            }
            
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_CUSTOMER, // Default role for new registrations
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
