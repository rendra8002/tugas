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
    public function getDendaRealtimeAttribute()
    {
        if ($this->status === 'returned') {
            return $this->total_denda;
        }

        if (!$this->jatuh_tempo) return 0;

        $jatuhTempo = Carbon::parse($this->jatuh_tempo)->startOfDay();

        $waktuSelesai = ($this->status === 'verifikasi' && $this->tanggal_kembali)
            ? Carbon::parse($this->tanggal_kembali)->startOfDay()
            : Carbon::now()->startOfDay();

        if ($waktuSelesai->gt($jatuhTempo)) {
            $selisihHari = $waktuSelesai->diffInDays($jatuhTempo);
            return $selisihHari * 2000;
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
