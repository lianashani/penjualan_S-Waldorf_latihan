<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $primaryKey = 'id_image';

    protected $fillable = [
        'id_produk',
        'id_variant',
        'gambar',
        'alt_text',
        'urutan',
        'is_primary',
        'is_active'
    ];

    protected $casts = [
        'urutan' => 'integer',
        'is_primary' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if ($image->is_primary) {
                // Ensure only one primary image per product
                static::where('id_produk', $image->id_produk)
                      ->where('is_primary', true)
                      ->update(['is_primary' => false]);
            }
        });

        static::saved(function ($image) {
            if ($image->is_primary) {
                // Ensure only one primary image per product
                static::where('id_produk', $image->id_produk)
                      ->where('id_image', '!=', $image->id_image)
                      ->where('is_primary', true)
                      ->update(['is_primary' => false]);
            }
        });

        static::deleted(function ($image) {
            // Delete physical file
            if ($image->gambar && Storage::disk('public')->exists($image->gambar)) {
                Storage::disk('public')->delete($image->gambar);
            }
        });
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'id_variant', 'id_variant');
    }

    public function getImageUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->gambar) return null;

        $path = pathinfo($this->gambar);
        $thumbnail = $path['dirname'] . '/' . $path['filename'] . '_thumb.' . $path['extension'];

        return Storage::disk('public')->exists($thumbnail)
            ? asset('storage/' . $thumbnail)
            : $this->image_url;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id_image');
    }
}
