<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // Tambahkan ini

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dummy Kepala Perpustakaan
        User::create([
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_perpustakaan',
            'image' => null,
        ]);

        // 2. Data Dummy Petugas
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'image' => null,
        ]);

        // --- PROSES AMBIL GAMBAR DARI FOLDER BG ---
        $path = public_path('assets/bg');
        $files = File::exists($path) ? File::files($path) : [];

        // 3. Buat 3 Data Dummy Anggota dengan gambar dari folder bg
        $anggotaData = [
            ['name' => 'Ruphas Anggota', 'email' => 'ruphas@perpus.com'],
            ['name' => 'Siswa Anggota', 'email' => 'anggota@perpus.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@perpus.com'],
        ];

        foreach ($anggotaData as $data) {
            // Ambil 1 nama file secara acak jika folder ada isinya
            $randomImage = !empty($files)
                ? 'assets/bg/' . $files[array_rand($files)]->getFilename()
                : null;

            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'image' => $randomImage, // Sekarang fotonya otomatis pake yang di folder bg
            ]);
        }
    }
}
