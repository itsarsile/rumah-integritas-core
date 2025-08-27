<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    public function auditReport()
    {
        return $this->belongsTo(AuditReports::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'desc');
    }

    public function participants()
    {
        return $this->hasMany(ChatRoomParticipant::class);
    }

    public function activeParticipants()
    {
        return $this->participants()->whereNull('left_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }
}
