<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LogisticsEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    protected $logistics;

    // Inject our LogisticsEngine to automatically sync location states on login
    public function __construct(LogisticsEngine $logistics)
    {
        $this->logistics = $logistics;
    }

    /**
     * Handle a secure registration request
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'pincode'  => 'required|string|size:6'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'pincode'  => $validated['pincode'],
        ]);

        Auth::login($user);

        // Sync session tracking with the user's permanent profile location token
        session()->put('user_delivery_pincode', $user->pincode);

        return redirect()->route('home');
    }

    /**
     * Handle a secure session authentication request
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            // If the user has a saved profile pincode, instantly apply it to the session landscape
            if (!empty($user->pincode)) {
                session()->put('user_delivery_pincode', $user->pincode);
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * End the active session securely
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}