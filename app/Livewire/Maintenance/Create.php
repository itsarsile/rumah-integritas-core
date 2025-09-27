<?php

namespace App\Livewire\Maintenance;

use App\Models\AssetType;
use App\Models\Division;
use App\Models\MaintenanceReports;
use App\Models\MaintenanceReportImage;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $assets;
    public $divisions;

    public $assetId;
    public $priority;
    public $divisionId;
    public $description;
    public $files = [];

    public function removeFile(int $index): void
    {
        if (isset($this->files[$index])) {
            array_splice($this->files, $index, 1);
        }
    }

    public function mount()
    {
        $this->divisions = Division::all();
        $this->assets = AssetType::all();
    }

    public function submit()
    {
        $this->validate([
            'assetId'     => 'required|exists:asset_types,id',
            'priority'    => 'required|in:low,medium,high',
            'divisionId'  => 'required|exists:divisions,id',
            'description' => 'nullable|string|max:2000',
            'files.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // max 10MB
        ]);

        // Buat kode request unik
        $requestCode = 'REQ-' . now()->format('Ymd-His') . '-' . Str::random(5);

        // Simpan laporan pemeliharaan
        $report = MaintenanceReports::create([
            'request_code'  => $requestCode,
            'asset_type_id' => $this->assetId,
            'priority'      => $this->priority,
            'description'   => $this->description,
            'division_id'   => $this->divisionId,
            'status'        => 'pending',
            'created_by'    => Auth::id(),
        ]);

        // Simpan file upload (max 3 foto)
        if ($this->files) {
            foreach (array_slice($this->files, 0, 3) as $file) {
                $path = $file->store('maintenance-reports', 'public');

                MaintenanceReportImage::create([
                    'maintenance_report_id' => $report->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Log activity: submission
        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => 'maintenance',
            'action' => 'submitted',
            'entity_type' => MaintenanceReports::class,
            'entity_id' => $report->id,
            'description' => 'Mengajukan permintaan pemeliharaan (' . $report->request_code . ')',
            'metadata' => [
                'asset_type_id' => $this->assetId,
                'division_id' => $this->divisionId,
                'priority' => $this->priority,
            ],
        ]);

        session()->flash('message', 'Permintaan pemeliharaan berhasil diajukan!');
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->assetId = null;
        $this->priority = null;
        $this->divisionId = null;
        $this->description = null;
        $this->files = [];
    }

    #[Title("Manajemen Pemeliharaan")]
    public function render()
    {
        return view('livewire.maintenance.create');
    }
}
