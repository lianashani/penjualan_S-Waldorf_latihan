<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberOrder extends Model
{
    protected $table = 'member_orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'id_member',
        'order_number',
        'payment_method',
        'snap_token',
        'transaction_id',
        'payment_type',
        'payment_status',
        'paid_at',
        'status',
        'subtotal',
        'total',
        'debt_due_at',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'debt_due_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MemberOrderItem::class, 'id_order', 'id_order');
    }
}
