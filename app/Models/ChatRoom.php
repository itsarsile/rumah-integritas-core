<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'audit_report_id',
        'room_code',
        'title',
        'description',
        'status',
        'created_by',
        'archived_at',
    ];
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
