<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $guarded = [];
    
    // Logic: Jika stok 0, status otomatis dianggap 'not-avaiable'
    public function getStatusAttribute($value)
    {
        if ($this->stock <= 0) {
            return 'not avaiable';
        }
        return 'avaiable';
    }
}
