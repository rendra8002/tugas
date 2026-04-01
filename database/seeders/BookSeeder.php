<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path gambar default sesuai permintaanmu
        $defaultImage = 'assets/r/557779963_3486861214786610_5644905288489553813_n (1).jpg';

        $books = [
            [
                'title'       => 'Filosofi Teras',
                'image'       => $defaultImage,
                'description' => 'Filsafat Yunani-Romawi Kuno untuk Mental Tangguh Masa Kini.',
                'author'      => 'Henry Manampiring',
                'stock'       => 10,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Laskar Pelangi',
                'image'       => $defaultImage,
                'description' => 'Kisah perjuangan 10 anak Belitung menuntut ilmu.',
                'author'      => 'Andrea Hirata',
                'stock'       => 5,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Atomic Habits',
                'image'       => $defaultImage,
                'description' => 'Perubahan Kecil yang Memberikan Hasil Luar Biasa.',
                'author'      => 'James Clear',
                'stock'       => 0,
                'status'      => 'not avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Bumi',
                'image'       => $defaultImage,
                'description' => 'Petualangan Raib, Seli, dan Ali di dunia paralel.',
                'author'      => 'Tere Liye',
                'stock'       => 8,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Mantappu Jiwa',
                'image'       => $defaultImage,
                'description' => 'Buku latihan soal kehidupan ala Jerome Polin.',
                'author'      => 'Jerome Polin Sijabat',
                'stock'       => 3,
                'status'      => 'avaiable',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        // Masukkan data ke database
        DB::table('books')->insert($books);
    }
}
