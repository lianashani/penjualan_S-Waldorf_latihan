<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberChat extends Model
{
    protected $table = 'member_chats';
    protected $primaryKey = 'id_chat';

    protected $fillable = [
        'id_member',
        'id_user',
        'message',
        'sender_type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
