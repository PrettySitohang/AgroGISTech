<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService; // Tambahkan ini
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $userExists = true;

        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
            Auth::login($user);
            // Log saat login/link akun
            if (class_exists(LogService::class) && method_exists(LogService::class, 'record')) {
                LogService::record('user.login.google', 'user', $user->id, ['email' => $user->email]);
            }
        } else {
            $userExists = false; // User baru
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password'  => null,
                'role'      => 'penulis',
            ]);

            Auth::login($user);
            // Log saat register
            if (class_exists(LogService::class) && method_exists(LogService::class, 'record')) {
                LogService::record('user.register.google', 'user', $user->id, ['email' => $user->email]);
            }
        }

        $role = Auth::user()->role;
        $message = $userExists ? 'Selamat datang kembali!' : 'Selamat datang di AgroGISTech!'; // Pesan yang lebih umum

        // Tentukan pesan yang lebih spesifik berdasarkan peran
        if ($role === 'super_admin') {
            $message = 'Selamat datang Admin!';
        } elseif ($role === 'editor') {
            $message = 'Selamat datang Editor!';
        }

        return match ($role) {
            'super_admin' => redirect()->route('admin.dashboard')->with('success', $message),
            'editor'      => redirect()->route('editor.dashboard')->with('success', $message),
            default       => redirect()->route('penulis.dashboard')->with('success', $message),
        };
    }
}
