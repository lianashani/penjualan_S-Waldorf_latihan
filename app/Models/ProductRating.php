<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRating extends Model
{
    protected $table = 'product_ratings';
    protected $primaryKey = 'id_rating';

    protected $fillable = [
        'id_produk',
        'id_user',
        'nama_pengguna',
        'email_pengguna',
        'rating',
        'komentar',
        'is_approved',
        'is_verified_purchase'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($rating) {
            // Update product rating averages
            $rating->updateProductRating();
        });

        static::deleted(function ($rating) {
            // Update product rating averages
            $rating->updateProductRating();
        });
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function updateProductRating()
    {
        $produk = $this->produk;
        if ($produk) {
            $approvedRatings = $produk->ratings()->where('is_approved', true);

            $produk->rating_count = $approvedRatings->count();
            $produk->rating_average = $approvedRatings->avg('rating') ?? 0;

            $produk->save();
        }
    }

    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingTextAttribute()
    {
        switch ($this->rating) {
            case 1:
                return 'Sangat Buruk';
            case 2:
                return 'Buruk';
            case 3:
                return 'Cukup';
            case 4:
                return 'Baik';
            case 5:
                return 'Sangat Baik';
            default:
                return '';
        }
    }

    public function getDisplayNameAttribute()
    {
        return $this->user ? $this->user->name : $this->nama_pengguna;
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }
}
