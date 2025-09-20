<?php

namespace App\Livewire\Maintenance;

use App\Models\MaintenanceReports;
use Livewire\Attributes\Title;
use Livewire\Component;
use Debugbar;

class Show extends Component
{
    public $id;
    public $maintenance;

    public function mount($id)
    {
        $this->id = $id;
        $this->maintenance = MaintenanceReports::with(['creator', 'divisions', 'assetType', 'images'])->findOrFail($id);
        Debugbar::info($this->maintenance);
    }

    #[Title('Detail Pemeliharaan')]
    public function render()
    {
        return view('livewire.maintenance.show');
    }
}
