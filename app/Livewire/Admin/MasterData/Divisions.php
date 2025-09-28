<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Division;
use Livewire\Attributes\Title;
use Livewire\Component;

class Divisions extends Component
{
    public string $name = '';
    public string $code = '';
    public $parent_div_id = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        $unique = 'unique:divisions,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:100',
            'code' => 'required|string|min:2|max:20|' . $unique,
            'parent_div_id' => 'nullable|integer|exists:divisions,id',
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            Division::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Divisi berhasil diperbarui');
        } else {
            Division::create($data);
            session()->flash('success', 'Divisi berhasil dibuat');
        }
        $this->cancelEdit();
        $this->dispatch('division-saved');
    }

    public function edit(int $id): void
    {
        $d = Division::findOrFail($id);
        $this->editingId = $d->id;
        $this->name = (string) $d->name;
        $this->code = (string) $d->code;
        $this->parent_div_id = $d->parent_div_id;
    }

    public function cancelEdit(): void
    {
        $this->reset(['name','code','parent_div_id','editingId']);
    }

    #[Title("Master Data - Divisi")]
    public function render()
    {
        return view('livewire.admin.master-data.divisions', [
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }
}
