<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember-me'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect sesuai role
            $redirectRoute = match ($user->role) {
                'admin'   => 'admin.dashboard',
                'mentor'  => 'mentor.dashboard',
                'investor'=> 'investor.dashboard',
                'user'    => 'main.dashboard',
                default   => 'main.dashboard',
            };

            return redirect()
                ->intended(route($redirectRoute))
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'))->with('info', 'Anda telah logout.');
    }
}
