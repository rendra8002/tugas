<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $fillable = [
        'user_id',
        'book_id',
        'jumlah',
        'status',
        'tanggal_pinjam',
        'jatuh_tempo',
        'tanggal_kembali',
        'total_denda',
        'bukti_pembayaran',
        'is_printed', // <--- WAJIB TAMBAHIN INI
    ];

    // ... di dalam class Peminjaman ...

    // 1. Tambahkan Appends supaya denda_realtime otomatis muncul di JSON/Array
    protected $appends = ['denda_realtime'];

    // 2. Buat Fungsi Accessor
    // Di dalam Model Peminjaman.php
    public function getDendaRealtimeAttribute()
    {
        if ($this->status === 'returned') {
            return abs((int)$this->total_denda); // Paksa positif saat ambil dari DB
        }

        $jatuhTempo = \Carbon\Carbon::parse($this->jatuh_tempo)->startOfDay();
        $hariIni = \Carbon\Carbon::now()->startOfDay();

        if ($hariIni->gt($jatuhTempo)) {
            $selisihHari = $hariIni->diffInDays($jatuhTempo);
            return $selisihHari * 2000; // Hasil pasti positif karena gt (greater than)
        }

        return 0;
    }

    // RELASI KE USER (Ini yang bikin error tadi kalau hilang)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE BOOK
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
