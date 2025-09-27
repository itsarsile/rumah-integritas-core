<?php

namespace App\Livewire\Agenda;
use App\Models\AgendaReports;
use Livewire\Attributes\Title;

use Livewire\Component;

class Show extends Component
{
    public $id;
    public $agenda;
    public function mount($id)
    {
        $this->id = $id;
        $this->agenda = AgendaReports::with('creator')->findOrFail($id);
    }

    #[Title(content: 'Detail Agenda')]
    public function render()
    {
        return view('livewire.agenda.show');
    }
}
