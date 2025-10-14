<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'category_id',
        'nama',
        'sku',
        'harga_beli',
        'harga_jual',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
