<?php

namespace App\Livewire\Calendar;

use App\Models\Division;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Kalender Divisi')]
class Board extends Component
{
    public array $modules = [
        'all' => 'Semua Modul',
        'consumption' => 'Konsumsi',
        'maintenance' => 'Pemeliharaan',
        'agenda' => 'Agenda',
    ];

    public string $module = 'all';
    public ?int $selectedDivisionId = null;
    public bool $canMark = false;
    public $divisions;

    public function mount(): void
    {
        $user = auth()->user();
        $this->selectedDivisionId = $user?->division_id;
        $this->divisions = Division::orderBy('name')->get(['id','name','code']);
        $this->canMark = (bool) ($user?->hasRole('admin') || $user?->hasRole('manager'));
    }

    public function updatedModule(): void {}
    public function updatedSelectedDivisionId(): void {}

    public function render()
    {
        return view('livewire.calendar.board');
    }
}

