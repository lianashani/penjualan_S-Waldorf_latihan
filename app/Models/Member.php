<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use Notifiable;

    protected $table = 'members';
    protected $primaryKey = 'id_member';
    
    protected $fillable = [
        'nama_member',
        'email',
        'password',
        'no_hp',
        'alamat',
        'photo',
        'points',
        'total_spent',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'points' => 'integer',
        'total_spent' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(MemberOrder::class, 'id_member', 'id_member');
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'id_member', 'id_member');
    }

    public function addPoints($points, $description, $orderId = null)
    {
        $this->increment('points', $points);
        
        PointTransaction::create([
            'id_member' => $this->id_member,
            'id_order' => $orderId,
            'type' => 'earned',
            'points' => $points,
            'description' => $description
        ]);
    }

    public function redeemPoints($points, $description, $orderId = null)
    {
        if ($this->points < $points) {
            throw new \Exception('Poin tidak mencukupi');
        }

        $this->decrement('points', $points);
        
        PointTransaction::create([
            'id_member' => $this->id_member,
            'id_order' => $orderId,
            'type' => 'redeemed',
            'points' => $points,
            'description' => $description
        ]);
    }

    public function getPointsValue()
    {
        // 100 points = Rp 10.000
        return ($this->points / 100) * 10000;
    }
}
