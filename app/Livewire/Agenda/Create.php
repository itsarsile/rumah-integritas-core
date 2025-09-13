<?php

namespace App\Livewire\Agenda;

use App\Models\AgendaReports;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    public $title;
    public $date;
    public $location;
    public $reviewed_by;

    public $personInCharges;

    public function mount()
    {
        $this->personInCharges = User::all();
    }

    public function submit()
    {
        $this->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'reviewed_by' => 'nullable|exists:users,id',
        ]);

        $requestCode = 'AGENDA-' . now()->format('Ymd-His') . '-' . Str::random(5);

        AgendaReports::create([
            'title'        => $this->title,
            'request_code' => $requestCode,
            'date'         => $this->date,
            'location'     => $this->location,
            'created_by'   => Auth::id(),
            'reviewed_by'  => $this->reviewed_by,
            'status'       => 'pending',
        ]);

        session()->flash('success', 'Agenda berhasil dibuat');
    }

    #[Title("Buat Agenda")]
    public function render()
    {
        return view('livewire.agenda.create');
    }
}
