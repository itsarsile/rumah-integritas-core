<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class MasterRequest extends Component
{
    #[Title("Manajemen Persetujuan")]
    public function render()
    {
        return view('livewire.master-request');
    }
}
