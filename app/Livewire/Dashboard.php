<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard', [
            'title' => 'Dashboard',
        ]);
    }
}
