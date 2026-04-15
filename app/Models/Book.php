<?php

namespace App\Models;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $guarded = ['id'];
    
    // Tambahkan casting untuk stock
    protected $casts = [
        'stock' => 'integer',
    ];

    /**
     * Accessor Status
     * Logic: Jika stok <= 0 (termasuk minus), tampil 'not avaiable'
     */
    // public function getStatusAttribute($value)
    // {
    //     if ($this->stock <= 0) {
    //         return 'not avaiable';
    //     }
    //     return $value;
    // }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'book_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
