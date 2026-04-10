<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tangkap URL sebelum masuk ke profil dan simpan ke session.
        // Syaratnya: Jangan timpa session kalau user cuma nge-refresh halaman profil ini.
        if (url()->previous() !== request()->url()) {
            session(['url_sebelum_profil' => url()->previous()]);
        }

        return view('pages.backend.profile.index', compact('user'));
    }
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'image' => 'nullable|image|max:2048' // Validasi file normal bisa dipakai lagi
        ]);

        // 1. Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // 2. Logika Ganti Foto 
        if ($request->hasFile('image')) {
            // Hapus gambar lama JIKA ada, DAN pastikan itu BUKAN gambar dari folder assets (seeder)
            if ($user->image && !str_starts_with($user->image, 'assets')) {
                // Gunakan disk('public') agar jalurnya dijamin lari ke storage/app/public/
                Storage::disk('public')->delete($user->image);
            }

            // Simpan gambar baru
            $user->image = $request->file('image')->store('users', 'public');
        }

        // 3. Logika Ganti Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $redirectUrl = session()->pull('url_sebelum_profil', route('backend.home.index'));
        return redirect($redirectUrl)->with('success', 'Profil berhasil diperbarui!');
    }
}
