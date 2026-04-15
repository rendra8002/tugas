<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use App\Models\User; // Tambahkan ini

class AuthController extends Controller
{
    // === FITUR LOGIN ===
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'kepala_perpustakaan' || $user->role === 'petugas') {
                // FIX: Ubah jadi dashboard.admin sesuai route web.php kamu
                return redirect()->route('dashboard.admin');
            } else {
                return redirect()->intended('/');
            }
        }
        return back()->with('error', 'Email atau Password salah!')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // === FITUR REGISTER BARU ===
    public function showRegisterForm()
    {
        return view('auth.register'); // Pastikan letak file balde ada di views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' butuh field 'password_confirmation' di HTML
            'agree' => 'accepted' // Wajib centang S&K
        ]);

        // 2. Masukkan ke Database & Paksa role jadi 'anggota'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => 'anggota', // <--- INI KUNCI UTAMANYA: Paksa otomatis anggota
        ]);

        // 3. Otomatiskan Login setelah sukses mendaftar
        Auth::login($user);

        // 4. Arahkan ke halaman utama frontend
        return redirect()->route('index')->with('success', 'Registrasi berhasil! Selamat datang di Perpustakaan.');
    }
}
