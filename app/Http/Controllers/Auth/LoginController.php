<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Jika user sudah login saat akses halaman login, cek role dan redirect
        if (Auth::check()) {
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

            return $this->redirectBasedOnRole(Auth::user())
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Helper function untuk redirect
    protected function redirectBasedOnRole($user)
    {
        $route = match ($user->role) {
            'admin'    => 'admin.dashboard',
            'mentor'   => 'mentor.dashboard',
            'investor' => 'investor.dashboard',
            'user'     => 'landing', // UBAH DISINI: User kembali ke landing page
            default    => 'landing',
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
