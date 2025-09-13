<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceReportImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_report_id',
        'image_path',
    ];

    public function report()
    {
        return $this->belongsTo(MaintenanceReports::class, 'maintenance_report_id');
    }
}
