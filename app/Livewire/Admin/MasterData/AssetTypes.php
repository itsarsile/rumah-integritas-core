<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\AssetType;
use Livewire\Attributes\Title;
use Livewire\Component;

class AssetTypes extends Component
{
    public string $name = '';
    public string $code = '';
    public ?string $description = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        $unique = 'unique:asset_types,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:100',
            'code' => 'required|string|min:2|max:20|' . $unique,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            AssetType::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Jenis aset berhasil diperbarui');
        } else {
            AssetType::create($data);
            session()->flash('success', 'Jenis aset berhasil dibuat');
        }
        $this->cancelEdit();
        $this->dispatch('asset-type-saved');
    }

    public function edit(int $id): void
    {
        $row = AssetType::findOrFail($id);
        $this->editingId = $row->id;
        $this->name = (string) $row->name;
        $this->code = (string) $row->code;
        $this->description = $row->description;
    }

    public function cancelEdit(): void
    {
        $this->reset(['name','code','description','editingId']);
    }

    #[Title("Master Data - Jenis Peralatan")]
    public function render()
    {
        return view('livewire.admin.master-data.asset-types', [
            'rows' => AssetType::orderBy('name')->paginate(10),
        ]);
    }
}
