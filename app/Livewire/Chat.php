<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Chat extends Component
{
    #[Title('Percakapan')]
    public function render()
    {
        return view('livewire.chat');
    }
}
