<?php

namespace App\Livewire\Consumption;

use App\Models\ConsumptionReport;
use App\Models\ConsumptionType;
use Livewire\Attributes\Title;
use Livewire\Component;
use Debugbar;

class Show extends Component
{
    public $id;
    public $consumption;
    public function mount($id)
    {
        $this->id = $id;
        $this->consumption = ConsumptionReport::with('creator', 'divisions', 'consumptionType')->findOrFail($id);
        Debugbar::info('Detail Konsumsi', ['data' => $this->consumption]);

    }

    #[Title('Detail Konsumsi')]
    public function render()
    {
        return view('livewire.consumption.show');
    }
}
