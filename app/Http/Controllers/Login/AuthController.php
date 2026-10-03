<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.login.login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Hanya akun berstatus aktif yang boleh login
        $credentials = $request->only('username', 'password');
        $credentials['status'] = 'aktif';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil');
            }

            // Route dashboard pelajar belum dibuat di web.php; dicek dulu agar tidak error.
            if ($user->isPelajar() && Route::has('pelajar.dashboard')) {
                return redirect()->route('pelajar.dashboard')->with('success', 'Login berhasil');
            }

            // Fallback: role tidak dikenali / halaman pelajar belum tersedia
            $pesan = $user->isPelajar()
                ? 'Halaman pelajar belum tersedia.'
                : 'Role tidak dikenali, hubungi administrator.';

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['username' => $pesan]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah, atau akun tidak aktif.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
