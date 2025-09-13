<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumptionReport extends Model
{
    use HasFactory;

    protected $casts = [
        'event_request_date' => 'date',
    ];
    
    protected $fillable = [
        'request_code',
        'request_title',
        'event_request_date',
        'audience_count',
        'email',
        'consumption_type_id',
        'division_id',   // ✅ add this
        'description',
        'created_by',    // ✅ add this
        'status',        // ✅ add this
        'created_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function divisions()
    {
        return $this->belongsTo(Division::class, "division_id");
    }

    public function consumptionType()
    {
        return $this->belongsTo(ConsumptionType::class, "consumption_type_id");
    }
}
