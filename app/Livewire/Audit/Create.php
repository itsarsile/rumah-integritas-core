<?php

namespace App\Livewire\Audit;

use App\Models\AuditReports;
use App\Models\RegionalGovernmentOrganization;
use Livewire\Attributes\Layout;
use App\Models\ActivityLog;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Buat Laporan Audit')]
class Create extends Component
{
    use WithFileUploads;

    public ?array $opd = null;

    public ?int $selectedOpd = null;

    public string $lhpNumber = '';

    public string $title = '';

    public string $description = '';

    #[Validate(['files.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'])]
    /**
     * Summary of files
     * @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[]
     */
    public array $files;

    public ?int $findings;


    public function loadOPD()
    {
        $this->opd = RegionalGovernmentOrganization::get()->toArray();
    }

    public function mount()
    {
        $this->loadOPD();
    }

    public function create()
    {
        try {
            $this->validate([
                'selectedOpd' => 'required|exists:regional_government_organizations,id',
                'lhpNumber' => 'required|string|max:100|unique:audit_reports,lhp_number',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'findings' => 'required|in:0,1',
            ]);

            $reportCode = $this->generateReportCode();

            $auditData = [
                'report_code' => $reportCode,
                'regional_government_organization_id' => $this->selectedOpd,
                'lhp_number' => $this->lhpNumber,
                'report_title' => $this->title,
                'report_description' => $this->description,
                'findings_count' => (int) $this->findings,
                'status' => 'pending',
                'created_by' => auth()->id(),
            ];

            if (!empty($this->files)) {
                foreach ($this->files as $file) {
                    $path = $file->store('audit_reports', 'local');
                    $auditData['lhp_document_path'] = $path;
                    $auditData['lhp_document_name'] = $file->getClientOriginalName();
                    $auditData['lhp_document_mime_type'] = $file->getMimeType();
                    $auditData['lhp_document_size'] = $file->getSize();
                }
            }

            $report = AuditReports::create($auditData);

            // Activity log for audit submission
            ActivityLog::create([
                'user_id' => auth()->id(),
                'module' => 'audit',
                'action' => 'submitted',
                'entity_type' => AuditReports::class,
                'entity_id' => $report->id,
                'description' => 'Mengajukan laporan audit (' . $reportCode . ')',
                'metadata' => [
                    'lhp_number' => $this->lhpNumber,
                    'opd_id' => $this->selectedOpd,
                    'findings' => (int) $this->findings,
                ],
            ]);

            session()->flash('message', 'Audit report created successfully with code: ' . $reportCode);

            $this->reset(['selectedOpd', 'lhpNumber', 'title', 'description', 'files', 'findings']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create audit report: ' . $e->getMessage());
        }
    }

    public function generateReportCode(): string
    {
        // AUD-OPD-YYYYMMDD-uniq 
        return 'AUD-' . $this->selectedOpd . '-' . date('Ymd') . '-' . uniqid();

    }
    public function render()
    {
        return view('livewire.audit.create', [
            'opd' => $this->opd,
        ]);
    }
}
