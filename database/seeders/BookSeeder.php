<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Wajib ditambahkan
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua file dari public/assets/bg
        $bgPath = public_path('assets/bg');
        $bgImages = [];

        if (File::exists($bgPath)) {
            $files = File::files($bgPath);
            foreach ($files as $file) {
                // Simpan format path-nya: assets/bg/namafile.jpg
                $bgImages[] = 'assets/bg/' . $file->getFilename();
            }
        }

        // Fallback jika folder bg kebetulan kosong
        $defaultFallback = 'assets/img/no-cover.png';

        $books = [
            [
                'title'       => 'Filosofi Teras',
                // Ambil gambar random dari array bgImages
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Filsafat Yunani-Romawi Kuno untuk Mental Tangguh Masa Kini.',
                'author'      => 'Henry Manampiring',
                'year'        => 2019,
                'stock'       => 10,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Laskar Pelangi',
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Kisah perjuangan 10 anak Belitung menuntut ilmu.',
                'author'      => 'Andrea Hirata',
                'year'        => 2005,
                'stock'       => 5,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Atomic Habits',
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Perubahan Kecil yang Memberikan Hasil Luar Biasa.',
                'author'      => 'James Clear',
                'year'        => 2018,
                'stock'       => 0,
                'status'      => 'not avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Bumi',
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Petualangan Raib, Seli, dan Ali di dunia paralel.',
                'author'      => 'Tere Liye',
                'year'        => 2014,
                'stock'       => 8,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Mantappu Jiwa',
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Buku latihan soal kehidupan ala Jerome Polin.',
                'author'      => 'Jerome Polin Sijabat',
                'year'        => 2019,
                'stock'       => 3,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Alama Coba',
                'image'       => !empty($bgImages) ? $bgImages[array_rand($bgImages)] : $defaultFallback,
                'description' => 'Buku palsu.',
                'author'      => 'Polisi',
                'year'        => 2024,
                'stock'       => 2,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('books')->insert($books);
    }
}
