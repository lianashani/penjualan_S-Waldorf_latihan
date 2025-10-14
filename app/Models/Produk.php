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
        'qr_code',
        'harga_min',
        'harga_max',
        'total_stok',
        'rating_average',
        'rating_count',
        'has_variants',
        'slug',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_min' => 'decimal:2',
        'harga_max' => 'decimal:2',
        'stok' => 'integer',
        'total_stok' => 'integer',
        'rating_average' => 'decimal:2',
        'rating_count' => 'integer',
        'has_variants' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
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
            if (empty($produk->slug)) {
                $produk->slug = \Str::slug($produk->nama_produk);
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

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'id_produk', 'id_produk');
    }

    public function activeVariants(): HasMany
    {
        return $this->variants()->where('is_active', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'id_produk', 'id_produk');
    }

    public function activeImages(): HasMany
    {
        return $this->images()->where('is_active', true);
    }

    public function primaryImage(): HasMany
    {
        return $this->images()->where('is_primary', true);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class, 'id_produk', 'id_produk');
    }

    public function approvedRatings(): HasMany
    {
        return $this->ratings()->where('is_approved', true);
    }

    // Accessor methods
    public function getFormattedPriceAttribute()
    {
        if ($this->has_variants && $this->harga_min && $this->harga_max) {
            if ($this->harga_min == $this->harga_max) {
                return 'Rp. ' . number_format($this->harga_min, 0, ',', '.');
            }
            return 'Rp. ' . number_format($this->harga_min, 0, ',', '.') . ' - Rp. ' . number_format($this->harga_max, 0, ',', '.');
        }
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    public function getMainImageAttribute()
    {
        $primaryImage = $this->primaryImage()->first();
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        $firstImage = $this->activeImages()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        return $this->gambar ? asset('storage/' . $this->gambar) : asset('assets/images/placeholder-product.jpg');
    }

    public function getStockStatusAttribute()
    {
        $stock = $this->has_variants ? $this->total_stok : $this->stok;

        if ($stock <= 0) {
            return 'out_of_stock';
        } elseif ($stock <= 10) {
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

    public function getRatingStarsAttribute()
    {
        $rating = $this->rating_average;
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        return str_repeat('★', $fullStars) .
               str_repeat('☆', $halfStar) .
               str_repeat('☆', $emptyStars);
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('has_variants', false)->where('stok', '>', 0)
              ->orWhere('has_variants', true)->where('total_stok', '>', 0);
        });
    }
}
