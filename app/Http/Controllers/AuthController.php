<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function showLogin(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($this->attemptAdminLogin($request)) {
            return redirect()->intended('dashboard');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function attemptAdminLogin(Request $request): bool
    {
        $adminEmail = trim((string) env('ADMIN_EMAIL', 'admin@estateflow.com'));
        $adminPassword = (string) env('ADMIN_PASSWORD', 'Admin@1234');

        if ($adminEmail === '' || $adminPassword === '') {
            return false;
        }

        if (strtolower($request->input('email')) !== strtolower($adminEmail)) {
            return false;
        }

        if ($request->input('password') !== $adminPassword) {
            return false;
        }

        $adminUser = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrator',
                'password' => Hash::make($adminPassword),
            ]
        );

        Auth::login($adminUser, $request->boolean('remember'));
        $request->session()->regenerate();

        return true;
    }
}
