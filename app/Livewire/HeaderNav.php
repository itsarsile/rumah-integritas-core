<?php

namespace App\Livewire;

use Livewire\Component;

class HeaderNav extends Component
{
    public $title = 'Dashboard';
    public function render()
    {
        return view('livewire.header-nav', [
            'title' => $this->title,
        ]);
    }
}
