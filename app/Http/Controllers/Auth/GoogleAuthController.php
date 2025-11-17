<?php

namespace App\Http\Controllers\Auth;

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

    // Callback Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Cek email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Hubungkan akun lama ke Google
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // Buat user baru
                    $user = User::create([
                        'name'      => $googleUser->name,
                        'email'     => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar'    => $googleUser->avatar,
                        'password'  => Hash::make(uniqid()),
                        'role'      => 'user'
                    ]);
                }
            }

            Auth::login($user);

            return redirect(route('main.dashboard'))
                ->with('success', 'Login Google berhasil!');

        } catch (\Exception $e) {
            return redirect(route('login'))
                ->with('error', 'Gagal login dengan Google. Coba lagi.');
        }
    }
}
