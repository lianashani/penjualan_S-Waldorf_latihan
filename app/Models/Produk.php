<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    
    protected $fillable = [
        'nama_produk',
        'id_kategori',
        'ukuran',
        'warna',
        'stok',
        'harga',
        'deskripsi',
        'gambar',
        'barcode',
        'qr_code'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            if (empty($produk->barcode)) {
                // Generate barcode: PRD + timestamp + random
                $produk->barcode = 'PRD' . time() . rand(100, 999);
            }
            if (empty($produk->qr_code)) {
                $produk->qr_code = $produk->barcode;
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailPenjualans(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'id_produk', 'id_produk');
    }
}
