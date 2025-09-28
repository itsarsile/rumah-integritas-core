<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Division;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Divisions extends Component
{
    #[Validate('required|string|min:2|max:100')]
    public string $name = '';

    #[Validate('required|string|min:2|max:20|unique:divisions,code')]
    public string $code = '';

    #[Validate('nullable|integer|exists:divisions,id')]
    public $parent_div_id = null;

    public function save()
    {
        $data = $this->validate();
        Division::create($data);
        session()->flash('success', 'Division created');
        $this->reset(['name','code','parent_div_id']);
        $this->dispatch('division-created');
    }

    public function render()
    {
        return view('livewire.admin.master-data.divisions', [
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }
}
