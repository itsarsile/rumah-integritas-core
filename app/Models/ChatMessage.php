<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'chat_room_id',
        'message_type',
        'content',
        'file_path',
        'file_name',
        'file_size',
        'file_mime_type',
        'is_edited',
        'edited_at',
        'reply_to_message_id',
    ];
    
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function replyTo()
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_message_id');
    }
    
    public function replies()
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_message_id');
    }
}
