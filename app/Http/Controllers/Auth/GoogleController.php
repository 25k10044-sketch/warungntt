<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id')
            || ! config('services.google.client_secret')
            || ! config('services.google.redirect')) {
            return redirect()->route('login')->with(
                'error',
                'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.',
            );
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->with(
                'error',
                'Login Google dibatalkan. Silakan coba lagi atau masuk dengan email.',
            );
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            return redirect()->route('login')->with(
                'error',
                'Sesi login Google sudah kedaluwarsa atau cookie sesi tidak diterima. Mulai login dari halaman ini lagi, gunakan localhost secara konsisten, dan jangan buka ulang halaman callback.',
            );
        }

        $email = $googleUser->getEmail();
        $profile = $googleUser->getRaw();
        $verified = $profile['email_verified'] ?? $profile['verified_email'] ?? false;

        if (! $email || ! $verified) {
            return redirect()->route('login')->with(
                'error',
                'Akun Google tidak memiliki email terverifikasi yang dapat digunakan.',
            );
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $email,
                'email' => $email,
                'password' => null,
                'role' => 'pelanggan',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->google_id) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
