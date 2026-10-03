<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    public const STATUS_COMPLETED = 'selesai';
    public const STATUS_CANCELED = 'batal';
    public const STATUS_PENDING = 'pending';

    public const PAYMENT_PENDING = 'pending';

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

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        $level = strtolower((string) $user->level);

        if ($level === 'staff' || $level === 'kasir') {
            $query->whereHas('kasir', fn (Builder $cashierQuery) => $cashierQuery->where('level', $level));
        }

        return $query;
    }

    protected static function booted(): void
    {
        static::creating(function (Transaksi $transaction) {
            $transaction->kode_transaksi = 'TRX-' . strtoupper(Str::random(6));
            $transaction->tanggal = now();
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

