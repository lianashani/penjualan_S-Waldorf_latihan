<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualans';
    protected $primaryKey = 'id_penjualan';
    
    protected $fillable = [
        'id_user',
        'id_pelanggan',
        'id_promo',
        'total_bayar',
        'kembalian',
        'status_transaksi',
        'tanggal_transaksi',
        'id_member_order'
    ];

    protected $casts = [
        'total_bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tanggal_transaksi' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }

    public function detailPenjualans(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan', 'id_penjualan');
    }
}
