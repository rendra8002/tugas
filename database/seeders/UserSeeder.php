<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Data Dummy Kepala Perpustakaan
        User::create([
            'name' => 'Bapak Kepala',
            'email' => 'kepala@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_perpustakaan',
            'image' => null,
        ]);

        // 2. Data Dummy Petugas
        User::create([
            'name' => 'Mbak Petugas',
            'email' => 'petugas@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'image' => null,
        ]);

        // 3. Data Dummy Anggota
        User::create([
            'name' => 'Siswa Anggota',
            'email' => 'anggota@perpus.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'image' => null,
        ]);

        // (Opsional) Membuat 10 anggota random tambahan menggunakan Factory
        // Pastikan UserFactory Anda sudah disesuaikan jika ingin mengaktifkan kode di bawah ini:
        // \App\Models\User::factory(10)->create(['role' => 'anggota']);
    }
}
