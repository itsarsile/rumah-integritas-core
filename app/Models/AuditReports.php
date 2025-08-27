<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditReports extends Model
{
    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }
}
