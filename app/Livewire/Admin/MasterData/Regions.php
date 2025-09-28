<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Regions as RegionModel;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Regions extends Component
{
    #[Validate('required|string|min:2|max:100')]
    public string $name = '';

    #[Validate('required|string|min:2|max:10|unique:regions,code')]
    public string $code = '';

    #[Validate('required|in:province,city,district,village')]
    public string $type = 'city';

    #[Validate('nullable|string|max:10')]
    public ?string $postal_code = null;

    public function save()
    {
        $data = $this->validate();
        RegionModel::create($data);
        session()->flash('success', 'Region created');
        $this->reset(['name','code','type','postal_code']);
        $this->type = 'city';
        $this->dispatch('region-created');
    }

    public function render()
    {
        return view('livewire.admin.master-data.regions', [
            'recent' => RegionModel::latest()->limit(10)->get(),
        ]);
    }
}
