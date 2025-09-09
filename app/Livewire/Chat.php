<?php

namespace App\Livewire;

use App\Models\AuditReports;
use Livewire\Attributes\Title;
use Livewire\Component;

class Chat extends Component
{

    public $auditReports;

    public function getAuditReports()
    {
        $this->auditReports = AuditReports::with(['creator', 'regionalGovernmentOrganization'])->get();
    }
    
    public function mount()
    {
        $this->getAuditReports();
    }

    #[Title('Percakapan')]
    public function render()
    {
        return view('livewire.chat', [
            'auditReports' => $this->auditReports
        ]);
    }
}
