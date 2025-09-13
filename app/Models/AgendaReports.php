<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaReports extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'request_code',
        'date',
        'location',
        'created_by',
        'reviewed_at',
        'reviewed_by',
        'status',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
