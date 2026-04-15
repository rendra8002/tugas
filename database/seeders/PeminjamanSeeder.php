<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data ID User (Anggota) dan ID Buku yang sudah ada di database
        // Pastikan di database sudah ada minimal 1 user dan 1 buku
        $users = DB::table('users')->where('role', 'anggota')->pluck('id')->toArray();
        $books = DB::table('books')->pluck('id')->toArray();

        // Cegah error jika tabel user/buku masih kosong
        if (empty($users) || empty($books)) {
            $this->command->info('Silakan isi tabel users (role: anggota) dan books terlebih dahulu!');
            return;
        }

        $peminjamans = [];

        // 2. Kita buat 30 data peminjaman acak
        for ($i = 0; $i < 30; $i++) {

            // Generate tanggal pinjam acak dari 365 hari yang lalu sampai hari ini
            $tanggalPinjam = Carbon::now()->subDays(rand(1, 365));

            // Jatuh tempo adalah 7 hari setelah tanggal pinjam
            $jatuhTempo = (clone $tanggalPinjam)->addDays(7);

            // Buat probabilitas: 40% kemungkinan telat (kena denda), 60% tepat waktu
            $isLate = rand(1, 10) <= 4;

            if ($isLate) {
                // Skenario Telat: Kembali 1 sampai 14 hari SETELAH jatuh tempo
                $hariTelat = rand(1, 14);
                $tanggalKembali = (clone $jatuhTempo)->addDays($hariTelat);

                // Misal denda Rp 2.000 per hari keterlambatan
                $denda = $hariTelat * 2000;
                $bukti = 'assets/img/bukti_dummy_' . rand(1, 100) . '.jpg';
            } else {
                // Skenario Tepat Waktu: Kembali 1 sampai 7 hari dari tanggal pinjam
                $hariPinjam = rand(1, 7);
                $tanggalKembali = (clone $tanggalPinjam)->addDays($hariPinjam);

                $denda = 0;
                $bukti = null;
            }

            $peminjamans[] = [
                'user_id'          => $users[array_rand($users)],
                'book_id'          => $books[array_rand($books)],
                'jumlah'           => 1,
                'status'           => 'returned', // Kita set returned agar masuk riwayat
                'is_printed'       => rand(0, 1),
                'tanggal_pinjam'   => $tanggalPinjam->format('Y-m-d'),
                'jatuh_tempo'      => $jatuhTempo->format('Y-m-d'),
                'tanggal_kembali'  => $tanggalKembali->format('Y-m-d'),
                'total_denda'      => $denda,
                'bukti_pembayaran' => $bukti,
                'created_at'       => $tanggalPinjam->format('Y-m-d H:i:s'),
                'updated_at'       => $tanggalKembali->format('Y-m-d H:i:s'),
            ];
        }

        // Tambahkan beberapa data berstatus 'pending' & 'approve' di hari ini agar variatif
        $peminjamans[] = [
            'user_id'          => $users[array_rand($users)],
            'book_id'          => $books[array_rand($books)],
            'jumlah'           => 1,
            'status'           => 'approve',
            'is_printed'       => 0,
            'tanggal_pinjam'   => Carbon::now()->format('Y-m-d'),
            'jatuh_tempo'      => Carbon::now()->addDays(7)->format('Y-m-d'),
            'tanggal_kembali'  => null,
            'total_denda'      => 0,
            'bukti_pembayaran' => null,
            'created_at'       => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'       => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        // 3. Insert semua data ke database
        DB::table('peminjamans')->insert($peminjamans);
    }
}
