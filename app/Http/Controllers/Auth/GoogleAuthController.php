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
            /** @var \Laravel\Socialite\Contracts\User $googleUser */
            $googleUser = Socialite::driver('google')->user();

            // Cek berdasarkan google_id (Gunakan getId())
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Cek email (Gunakan getEmail())
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Hubungkan akun lama ke Google
                    $user->update(['google_id' => $googleUser->getId()]);
                } else {
                    // Buat user baru (Gunakan getter untuk semua field)
                    $user = User::create([
                        'name'      => $googleUser->getName(),
                        'email'     => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                        'password'  => Hash::make(uniqid()),
                        'role'      => 'user'
                    ]);
                }
            }

            Auth::login($user);

            // Redirect sesuai role
            $redirectRoute = match ($user->role) {
                'admin'   => 'admin.dashboard',
                'mentor'  => 'mentor.dashboard',
                'investor'=> 'investor.dashboard',
                'user'    => 'landing',
                default   => 'landing',
            };

            return redirect(route($redirectRoute))
                ->with('success', 'Login Google berhasil!');

        } catch (\Exception $e) {
            return redirect(route('login'))
                ->with('error', 'Gagal login dengan Google. Coba lagi.');
        }
    }
}
