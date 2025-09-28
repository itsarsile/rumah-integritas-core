<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Regions as RegionModel;
use Livewire\Attributes\Title;
use Livewire\Component;

class Regions extends Component
{
    public string $name = '';
    public string $code = '';
    public string $type = 'city';
    public ?string $postal_code = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        $unique = 'unique:regions,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:100',
            'code' => 'required|string|min:2|max:10|' . $unique,
            'type' => 'required|in:province,city,district,village',
            'postal_code' => 'nullable|string|max:10',
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            RegionModel::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Wilayah berhasil diperbarui');
        } else {
            RegionModel::create($data);
            session()->flash('success', 'Wilayah berhasil dibuat');
        }
        $this->cancelEdit();
        $this->dispatch('region-saved');
    }

    public function edit(int $id): void
    {
        $r = RegionModel::findOrFail($id);
        $this->editingId = $r->id;
        $this->name = (string) $r->name;
        $this->code = (string) $r->code;
        $this->type = (string) $r->type;
        $this->postal_code = $r->postal_code;
    }

    public function cancelEdit(): void
    {
        $this->reset(['name','code','type','postal_code','editingId']);
        $this->type = 'city';
    }

    #[Title("Master Data - Wilayah")]
    public function render()
    {
        return view('livewire.admin.master-data.regions', [
            'rows' => RegionModel::orderBy('name')->paginate(10),
        ]);
    }
}
