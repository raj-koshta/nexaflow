<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    /**
     * Attempt to log the user in.
     * 
     * @param array $credentials
     * @return bool
     */
    public function attemptLogin(array $credentials)
    {
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (Auth::attempt($credentials, $remember)) {
            Log::info('User logged in: ' . Auth::user()->email);
            return true;
        }

        return false;
    }

    /**
     * Register a new user.
     * 
     * @param array $data
     * @return User
     */
    public function registerUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Log::info('New user registered: ' . $user->email);
        
        // TODO: Fire UserRegistered Event here

        return $user;
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        if (Auth::check()) {
            Log::info('User logged out: ' . Auth::user()->email);
            Auth::logout();
        }
    }
}
