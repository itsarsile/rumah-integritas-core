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
        'priority',
    ];

    public function getPriorityTextAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'Penting',
            'medium' => 'Sedang',
            default => 'Rendah',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'badge-error',
            'medium' => 'badge-warning',
            default => 'badge-info',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'approved', 'accepted' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => (string) $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'approved', 'accepted' => 'badge-success',
            'rejected' => 'badge-error',
            default => 'badge-ghost',
        };
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function divisions()
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
