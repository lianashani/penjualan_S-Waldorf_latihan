<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $primaryKey = 'id_variant';

    protected $fillable = [
        'id_produk',
        'ukuran',
        'warna',
        'kode_warna',
        'stok',
        'harga',
        'sku',
        'is_active'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                // Generate SKU: Product ID + Size + Color
                $variant->sku = 'PRD' . $variant->id_produk . '-' .
                               strtoupper(substr($variant->ukuran, 0, 2)) . '-' .
                               strtoupper(substr($variant->warna, 0, 3));
            }
        });

        static::saved(function ($variant) {
            // Update product totals when variant is saved
            $variant->updateProductTotals();
        });

        static::deleted(function ($variant) {
            // Update product totals when variant is deleted
            $variant->updateProductTotals();
        });
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'id_variant', 'id_variant');
    }

    public function updateProductTotals()
    {
        $produk = $this->produk;
        if ($produk) {
            $variants = $produk->variants()->where('is_active', true);

            $produk->total_stok = $variants->sum('stok');
            $produk->harga_min = $variants->min('harga') ?? $produk->harga;
            $produk->harga_max = $variants->max('harga') ?? $produk->harga;
            $produk->has_variants = $variants->count() > 0;

            $produk->save();
        }
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->harga ?? $this->produk->harga, 0, ',', '.');
    }

    public function getStockStatusAttribute()
    {
        if ($this->stok <= 0) {
            return 'out_of_stock';
        } elseif ($this->stok <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusTextAttribute()
    {
        switch ($this->stock_status) {
            case 'out_of_stock':
                return 'Habis';
            case 'low_stock':
                return 'Stok Menipis';
            default:
                return 'Tersedia';
        }
    }
}
