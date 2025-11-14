<?php

namespace App\Http\Controllers\Auth; // Namespace diubah

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Jika user ada, langsung login
                Auth::login($user);
            } else {
                // Jika tidak ada, cek berdasarkan email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Jika email ada (register manual), update google_id
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // Jika user benar-benar baru, buat akun baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(uniqid()), // Buat password random
                        'role' => 'user' // Default role
                    ]);
                }
                Auth::login($user);
            }

            // Redirect ke dashboard
            return redirect(route('main.dashboard')); // Diubah

        } catch (\Exception $e) {
            // Jika gagal, kembali ke login dengan error
            return redirect(route('login'))->with('swal_error', 'Gagal login dengan Google. Coba lagi.');
        }
    }
}
