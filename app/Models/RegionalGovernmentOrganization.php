<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionalGovernmentOrganization extends Model
{
    protected $fillable = [
        'name', 'code', 'address', 'phone', 'email', 'website', 'status', 'region_id'
    ];
    public function auditReports()
    {
        return $this->hasMany(AuditReports::class);
    }

    public function regions()
    {
        return $this->belongsTo(Regions::class);
    }
}
