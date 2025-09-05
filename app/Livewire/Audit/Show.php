<?php

namespace App\Livewire\Audit;

use App\Models\AuditReports;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $id;
    public $audit;
    public $fileUrl;
    public $newFile;

    #[Validate(['newFile.*' => 'file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'])]
    public $showFileUpload;

    protected $messages = [
        'newFile.required' => 'File harus dipilih.',
        'newFile.file' => 'File yang dipilih tidak valid.',
        'newFile.mimes' => 'File harus berformat PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
        'newFile.max' => 'Ukuran file maksimal 10MB.',
    ];

    public function mount($id)
    {
        $this->id = $id;
        $this->audit = AuditReports::with(['creator', 'regionalGovernmentOrganization'])->findOrFail($id);
    }

    public function deleteFile()
    {
        try {
            if ($this->audit->hasLhpDocument()) {
                $filePath = 'app/private/' . $this->audit->lhp_document_path;
                
                if (Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                }

                $this->audit->update([
                    'lhp_document_name' => null,
                    'lhp_document_path' => null,
                    'lhp_document_size' => null,
                    'lhp_document_type' => null,
                ]);

                $this->audit->refresh();

                session()->flash('success', 'File berhasil dihapus.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    public function showUploadForm()
    {
        $this->showFileUpload = true;
        $this->newFile = null;
    }

    public function cancelUpload()
    {
        $this->showFileUpload = false;
        $this->newFile = null;
        $this->resetErrorBag();
    }

    public function uploadNewFile()
    {
        $this->validate();

        try {
            if ($this->audit->hasLhpDocument()) {
                $oldFilePath = 'app/audit_reports/' . $this->audit->lhp_document_path;
                if (Storage::disk('local')->exists($oldFilePath)) {
                    Storage::disk('local')->delete($oldFilePath);
                }
            }

            $originalName = $this->newFile->getClientOriginalName();
            $extension = $this->newFile->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uniqueFileName = $fileName . '_' . time() . '.' . $extension;

            $filePath = $this->newFile->storeAs('audit_reports', $uniqueFileName, 'local');

            $this->audit->update([
                'lhp_document_name' => $originalName,
                'lhp_document_path' => $filePath,
                'lhp_document_size' => $this->newFile->getSize(),
                'lhp_document_type' => $this->newFile->getMimeType(),
            ]);

            $this->audit->refresh();

            $this->showFileUpload = false;
            $this->newFile = null;

            session()->flash('success', 'File berhasil diupload.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    #[Title('Detail Audit Report')]
    public function render()
    {
        return view('livewire.audit.show');
    }
}
