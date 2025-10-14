<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    // Relasi ke barang
    public function barang()
    {
        return $this->hasMany(Barang::class, 'category_id');
    }
}
