<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(rand(100000, 999999)), // Random password
                    'email_verified_at' => now(), // Google users are automatically verified
                ]);
            } else {
                // Update existing user's google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
            }
            
            // Log the user in
            Auth::login($user);
            
            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong with Google login. Please try again.');
        }
    }
} 