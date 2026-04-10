<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    // Pakai guarded kosong oke, tapi pastiin kolomnya bener
    protected $guarded = ['id'];

    /**
     * Accessor Status
     * Logic: Jika stok 0, otomatis tampil 'not avaiable' di view
     */
    public function getStatusAttribute($value)
    {
        // Jika stok di DB 0, paksa tampilkan not avaiable
        if ($this->stock <= 0) {
            return 'not avaiable';
        }
        // Jika stok ada, kembalikan nilai asli dari DB (avaiable / not avaiable)
        return $value;
    }
}
