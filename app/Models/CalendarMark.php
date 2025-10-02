<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'division_id',
        'date',
        'label',
        'color',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

