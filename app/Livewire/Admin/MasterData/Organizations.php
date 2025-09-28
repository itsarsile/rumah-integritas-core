<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\RegionalGovernmentOrganization as Org;
use App\Models\Regions;
use Livewire\Attributes\Title;
use Livewire\Component;

class Organizations extends Component
{
    public string $name = '';
    public string $code = '';
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $website = null;
    public string $status = 'active';
    public $region_id = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        $unique = 'unique:regional_government_organizations,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:150',
            'code' => 'required|string|min:2|max:20|' . $unique,
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|url|max:150',
            'status' => 'required|in:active,inactive',
            'region_id' => 'required|integer|exists:regions,id',
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            Org::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Organisasi berhasil diperbarui');
        } else {
            Org::create($data);
            session()->flash('success', 'Organisasi berhasil dibuat');
        }
        $this->cancelEdit();
        $this->dispatch('organization-saved');
    }

    public function edit(int $id): void
    {
        $row = Org::findOrFail($id);
        $this->editingId = $row->id;
        $this->name = (string) $row->name;
        $this->code = (string) $row->code;
        $this->address = $row->address;
        $this->phone = $row->phone;
        $this->email = $row->email;
        $this->website = $row->website;
        $this->status = (string) $row->status;
        $this->region_id = $row->region_id;
    }

    public function cancelEdit(): void
    {
        $this->reset(['name','code','address','phone','email','website','status','region_id','editingId']);
        $this->status = 'active';
    }

    #[Title("Master Data - OPD")]
    public function render()
    {
        return view('livewire.admin.master-data.organizations', [
            'regions' => Regions::orderBy('name')->get(),
            'rows' => Org::with('regions')->orderBy('name')->paginate(10),
        ]);
    }
}
