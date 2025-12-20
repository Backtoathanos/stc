<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to home
        if (Auth::check()) {
            return redirect('/stc/stc_payroll/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') && $request->remember == 1;

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact administrator.'
            ], 403);
        }

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Double check status after authentication
            if ($user->status !== 'active') {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is inactive. Please contact administrator.'
                ], 403);
            }
            
            // Set cookie for 24 hours if remember is checked
            if ($remember) {
                // Refresh the user to get the remember_token that Laravel generates
                $user->refresh();
                $cookie = Cookie::make('remember_token', $user->remember_token, 1440); // 24 hours in minutes
                Cookie::queue($cookie);
            }
            
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => '/stc/stc_payroll/'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear remember cookie
        Cookie::queue(Cookie::forget('remember_token'));
        
        return redirect('/stc/stc_payroll/login');
    }
}

