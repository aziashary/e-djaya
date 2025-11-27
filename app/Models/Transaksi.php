<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'kasir_id',
        'subtotal',
        'diskon',
        'total',
        'metode_pembayaran',
        'makan_dimana',
        'nama_customer',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];
    

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_transaksi = 'TRX-' . strtoupper(Str::random(6));
            $model->tanggal = now();
        });
    }

    public function kasir()
{
    return $this->belongsTo(User::class, 'kasir_id', 'id');
}

    public function items()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_id', 'id');
    }
}

