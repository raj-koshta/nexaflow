<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->attemptLogin($request->validated())) {
            $request->session()->regenerate();
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => route('dashboard')]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.'], 401);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->registerUser($request->validated());
        
        Auth::login($user);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect' => url('/')]);
        }

        return redirect('/');
    }
}
