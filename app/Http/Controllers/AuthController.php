<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // Mengganti nama method 'register' menjadi 'storeUser'
    public function storeUser(Request $request)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:8',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'penulis'
            ]);

            // Pastikan LogService::record ada
            if (class_exists(LogService::class) && method_exists(LogService::class, 'record')) {
                LogService::record('user.register', 'user', $user->id, ['email' => $user->email]);
            }

            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Akun dibuat dan login.');
        }

        // Mengganti nama method 'login' menjadi 'authenticate'
        public function authenticate(Request $request)
            {
                $creds = $request->validate([
                    'email' => 'required|email',
                    'password' => 'required'
                ]);
                if (Auth::attempt($creds, $request->filled('remember'))) {
                    $request->session()->regenerate();

                    $user = Auth::user();
                    \Log::info('User login successful', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_role' => $user->role
                    ]);

                    if (class_exists(LogService::class) && method_exists(LogService::class, 'record')) {
                        LogService::record('user.login', 'user', $user->id);
                    }

                    // Redirect berdasarkan role
                    return match($user->role) {
                        'super_admin' => redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang di Admin Panel.'),
                        'editor' => redirect()->route('editor.dashboard')->with('success', 'Login berhasil! Selamat datang di Editor Panel.'),
                        'penulis' => redirect()->route('penulis.dashboard')->with('success', 'Login berhasil! Selamat datang di Dashboard Penulis.'),
                        default => redirect()->route('dashboard')->with('success', 'Login berhasil!')
                    };
                }

                \Log::warning('Login attempt failed', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);

                return back()
                    ->withErrors(['email' => 'Kredensial tidak cocok'])
                    ->withInput();
            }


    public function logout(Request $request)
    {
        // Pastikan LogService::record ada
        if (class_exists(LogService::class) && method_exists(LogService::class, 'record')) {
            LogService::record('user.logout', 'user', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.index');
    }
}
