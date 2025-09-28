<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\RegionalGovernmentOrganization as Org;
use App\Models\Regions;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Organizations extends Component
{
    #[Validate('required|string|min:2|max:150')]
    public string $name = '';

    #[Validate('required|string|min:2|max:20|unique:regional_government_organizations,code')]
    public string $code = '';

    #[Validate('nullable|string|max:500')]
    public ?string $address = null;

    #[Validate('nullable|string|max:50')]
    public ?string $phone = null;

    #[Validate('nullable|email|max:150')]
    public ?string $email = null;

    #[Validate('nullable|url|max:150')]
    public ?string $website = null;

    #[Validate('required|in:active,inactive')]
    public string $status = 'active';

    #[Validate('required|integer|exists:regions,id')]
    public $region_id = null;

    public function save()
    {
        $data = $this->validate();
        Org::create($data);
        session()->flash('success', 'Organization created');
        $this->reset(['name','code','address','phone','email','website','status','region_id']);
        $this->status = 'active';
        $this->dispatch('organization-created');
    }

    public function render()
    {
        return view('livewire.admin.master-data.organizations', [
            'regions' => Regions::orderBy('name')->get(),
            'recent' => Org::with('regions')->latest()->limit(10)->get(),
        ]);
    }
}
