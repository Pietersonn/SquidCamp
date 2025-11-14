<?php

namespace App\Http\Controllers\Auth; // Namespace diubah

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

        // Redirect ke route baru berdasarkan role
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'mentor':
                return redirect()->intended(route('mentor.dashboard'));
            case 'investor':
                return redirect()->intended(route('investor.dashboard'));
            case 'user':
                return redirect()->intended(route('main.dashboard'));
            default:
                return redirect()->intended(route('main.dashboard'));
        }
    }

    return back()->with('swal_error', 'Email atau password salah.');
  }

  public function logout(Request $request)
  {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect(route('login'));
  }
}
