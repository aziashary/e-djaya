<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;

    protected $table = 'kasir';

    protected $fillable = [
        'nama',
        'pin',
        'role',
        'status',
    ];

    protected $hidden = [
        'pin', // biar gak ke-expose waktu API JSON
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
