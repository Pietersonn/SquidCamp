<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller // Nama Class diubah
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    // Lokasi view diubah
    return view('auth.register', ['pageConfigs' => $pageConfigs]);
  }

  public function register(Request $request)
  {
      $request->validate([
          'username' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:8|confirmed',
      ]);

      $user = User::create([
          'name' => $request->username,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'role' => 'user',
      ]);

      Auth::login($user);

      // Redirect ke route user main
      return redirect(route('main.dashboard')); // Diubah
  }
}
