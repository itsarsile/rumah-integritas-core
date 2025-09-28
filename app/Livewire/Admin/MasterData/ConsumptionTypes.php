<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\ConsumptionType;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ConsumptionTypes extends Component
{
    #[Validate('required|string|min:2|max:100')]
    public string $name = '';

    #[Validate('required|string|min:2|max:20|unique:consumption_types,code')]
    public string $code = '';

    public function save()
    {
        $data = $this->validate();
        ConsumptionType::create($data);
        session()->flash('success', 'Consumption type created');
        $this->reset(['name','code']);
        $this->dispatch('consumption-type-created');
    }

    public function render()
    {
        return view('livewire.admin.master-data.consumption-types', [
            'recent' => ConsumptionType::latest()->limit(10)->get(),
        ]);
    }
}
