<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori'
    ];

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
    }

    public function activeProduks(): HasMany
    {
        return $this->produks()->where('is_active', true);
    }

    public function inStockProduks(): HasMany
    {
        return $this->activeProduks()->where(function($query) {
            $query->where('has_variants', false)->where('stok', '>', 0)
                  ->orWhere('has_variants', true)->where('total_stok', '>', 0);
        });
    }
}
