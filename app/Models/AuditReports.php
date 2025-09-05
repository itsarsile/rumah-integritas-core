<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AuditReports extends Model
{
    protected $fillable = [
        'report_code',
        'regional_government_organization_id',
        'lhp_number',
        'report_title',
        'report_description',
        'findings_count',
        'audit_start_date',
        'audit_end_date',
        'audit_type',
        'status',
        'lhp_document_path',
        'lhp_document_name',
        'lhp_document_mime_type',
        'lhp_document_size',
        'audit_scope',
        'findings_summary',
        'total_findings_amount',
        'lead_auditor',
        'audit_team',
        'created_by',
        'submitted_at',
        'reviewed_at',
        'published_at',
    ];

    protected $casts = [
        'audit_start_date' => 'date',
        'audit_end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
        'audit_scope' => 'array',
        'findings_summary' => 'array',
        'audit_team' => 'array',
        'total_findings_amount' => 'decimal:2',
        'lhp_document_size' => 'integer',
        'findings_count' => 'integer',
    ];

    public function regionalGovernmentOrganization()
    {
        return $this->belongsTo(RegionalGovernmentOrganization::class);
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the URL for the LHP document
     */
    public function getLhpDocumentUrlAttribute()
    {
        return $this->lhp_document_path ? Storage::disk('local')->url($this->lhp_document_path) : null;
    }

    /**
     * Get human-readable file size
     */
    public function getLhpDocumentSizeHumanAttribute()
    {
        if (!$this->lhp_document_size) {
            return null;
        }

        $bytes = $this->lhp_document_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if LHP document exists
     */
    public function hasLhpDocument(): bool
    {
        return !empty($this->lhp_document_path) && !empty($this->lhp_document_name);
    }

    /**
     * Scope for reports with findings
     */
    public function scopeWithFindings($query)
    {
        return $query->where('findings_count', '>', 0);
    }

    /**
     * Scope for reports without findings
     */
    public function scopeWithoutFindings($query)
    {
        return $query->where('findings_count', 0);
    }

    public function getFileUrl(): string
    {
        if ($this->hasLhpDocument()) {
            return route('audit.files', $this->id); // Use ID instead of path
        }
        return '';
    }
    
    public function getFilePath(): string
    {
        return storage_path('app/' . $this->lhp_document_path);
    }
    
    public function fileExists(): bool
    {
        return $this->hasLhpDocument() && file_exists($this->getFilePath());
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-warning',
            'in_progress' => 'badge-info',
            'completed' => 'badge-primary',
            'published' => 'badge-success',
            default => 'badge-neutral',
        };
    }
}