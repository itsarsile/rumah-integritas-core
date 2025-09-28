<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\AssetType;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AssetTypes extends Component
{
    #[Validate('required|string|min:2|max:100')]
    public string $name = '';

    #[Validate('required|string|min:2|max:20|unique:asset_types,code')]
    public string $code = '';

    #[Validate('nullable|string|max:255')]
    public ?string $description = null;

    public function save()
    {
        $data = $this->validate();
        AssetType::create($data);
        session()->flash('success', 'Asset type created');
        $this->reset(['name','code','description']);
        $this->dispatch('asset-type-created');
    }

    public function render()
    {
        return view('livewire.admin.master-data.asset-types', [
            'recent' => AssetType::latest()->limit(10)->get(),
        ]);
    }
}
