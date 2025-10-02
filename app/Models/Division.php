<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'name', 'code', 'parent_div_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}
