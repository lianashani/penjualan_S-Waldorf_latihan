<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';
    
    protected $fillable = [
        'nama_pelanggan',
        'email',
        'password',
        'status',
        'tanggal_daftar',
        'id_membership'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime'
    ];

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'id_membership', 'id_membership');
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
