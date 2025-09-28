<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\ConsumptionType;
use Livewire\Attributes\Title;
use Livewire\Component;

class ConsumptionTypes extends Component
{
    public string $name = '';
    public string $code = '';
    public ?int $editingId = null;

    protected function rules(): array
    {
        $unique = 'unique:consumption_types,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:100',
            'code' => 'required|string|min:2|max:20|' . $unique,
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            ConsumptionType::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Jenis konsumsi berhasil diperbarui');
        } else {
            ConsumptionType::create($data);
            session()->flash('success', 'Jenis konsumsi berhasil dibuat');
        }
        $this->cancelEdit();
        $this->dispatch('consumption-type-saved');
    }

    public function edit(int $id): void
    {
        $row = ConsumptionType::findOrFail($id);
        $this->editingId = $row->id;
        $this->name = (string) $row->name;
        $this->code = (string) $row->code;
    }

    public function cancelEdit(): void
    {
        $this->reset(['name','code','editingId']);
    }

    #[Title("Master Data - Jenis Konsumsi")]
    public function render()
    {
        return view('livewire.admin.master-data.consumption-types', [
            'rows' => ConsumptionType::orderBy('name')->paginate(10),
        ]);
    }
}
