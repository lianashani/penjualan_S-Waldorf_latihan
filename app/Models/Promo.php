<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $table = 'promos';
    protected $primaryKey = 'id_promo';
    
    protected $fillable = [
        'kode_promo',
        'persen',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    protected $casts = [
        'persen' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'id_promo', 'id_promo');
    }

    // Check if promo is currently valid
    public function isValid(): bool
    {
        $now = now()->toDateString();
        return $this->status === 'aktif' 
            && $this->tanggal_mulai <= $now 
            && $this->tanggal_selesai >= $now;
    }
}
