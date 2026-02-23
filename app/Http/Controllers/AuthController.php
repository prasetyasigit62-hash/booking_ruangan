<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan halaman form login
    public function showLoginForm()
    {
        // Jika admin sudah login, jangan biarkan kembali ke form login
        if (Auth::check()) {
            return redirect()->route('booking.monitoring');
        }

        return view('auth.login');
    }

    // 2. Memproses data yang diketik saat tombol login ditekan
    public function login(Request $request)
    {
        // Validasi form tidak boleh kosong
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        // Coba cocokkan dengan database
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Jika sukses, lempar langsung ke halaman Monitoring!
            return redirect()->intended(route('booking.monitoring'))
                ->with('success', 'Selamat datang kembali, Admin!');
        }

        // Jika gagal (email/password salah), kembalikan ke form bawa pesan error
        return back()->withErrors([
            'email' => 'Akses Ditolak! Email atau password tidak cocok dengan data Admin.',
        ])->onlyInput('email');
    }

    // 3. Memproses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
