<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Jika user sudah login dan buka halaman login lagi
        if (Auth::check()) {

            // Role user biasa -> kembali ke landing
            if (Auth::user()->role === 'user') {
                return redirect()->route('landing');
            }

            // Selain user -> redirect berdasarkan role
            return $this->redirectBasedOnRole(Auth::user());
        }

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

            // Jika role = user biasa → SELALU kembali ke landing
            if (Auth::user()->role === 'user') {
                return redirect()
                    ->route('landing')
                    ->with('success', 'Login berhasil! Selamat datang kembali.');
            }

            // Selain user -> gunakan role redirect
            return $this->redirectBasedOnRole(Auth::user())
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Redirect khusus untuk admin, mentor, investor
    protected function redirectBasedOnRole($user)
    {
        $route = match ($user->role) {
            'admin'    => 'admin.dashboard',
            'mentor'   => 'mentor.select-event',
            'investor' => 'investor.select-event',
            default    => 'landing', // fallback
        };

        return redirect()->intended(route($route));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('landing'))->with('info', 'Anda telah logout.');
    }
}
