<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];
    const UPDATED_AT = null;

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function quotedMessage()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }

}
