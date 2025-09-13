<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceReports extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'asset_type_id',
        'description',
        'created_by',
        'reviewed_at',
        'reviewed_by',
        'division_id',
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

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function images()
    {
        return $this->hasMany(MaintenanceReportImage::class, 'maintenance_report_id');
    }
}
