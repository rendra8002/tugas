<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function index()
    {
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // 1. Validasi inputan
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek apakah email dan password cocok
        if (Auth::attempt($credentials)) {
            // Jika berhasil, buat ulang session (keamanan)
            $request->session()->regenerate();

            // 3. Cek role user dan arahkan ke dashboard masing-masing
            $user = Auth::user();

            if ($user->role === 'kepala_perpustakaan') {
                return redirect()->route('backend.home.index');
            } elseif ($user->role === 'petugas') {
                // Redirect ke route bernama 'backend.home.index'
                return redirect()->route('backend.home.index');
            } else {
                // Untuk anggota biasa
                return redirect()->intended('/');
            }
        }

        // 4. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->with('error', 'Email atau Password salah!')->withInput();
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
