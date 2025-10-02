<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Division;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class Divisions extends Component
{
    public string $name = '';
    public string $code = '';
    public $parent_div_id = null;
    public ?int $editingId = null;

    // Manage users in division
    public bool $showUsersModal = false;
    public ?int $selectedDivisionId = null;
    public array $selectedUserIds = [];

    protected function rules(): array
    {
        $unique = 'unique:divisions,code' . ($this->editingId ? ',' . $this->editingId : '');
        return [
            'name' => 'required|string|min:2|max:100',
            'code' => 'required|string|min:2|max:20|' . $unique,
            'parent_div_id' => 'nullable|integer|exists:divisions,id',
        ];
    }

    public function mount(): void
    {
        // Admin-only access
        if (!auth()->user()?->hasRole('admin')) {
            abort(403);
        }
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

    public function openUsersModal(int $divisionId): void
    {
        $this->selectedDivisionId = $divisionId;
        $this->selectedUserIds = User::where('division_id', $divisionId)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->showUsersModal = true;
    }

    public function closeUsersModal(): void
    {
        $this->showUsersModal = false;
        $this->reset(['selectedDivisionId', 'selectedUserIds']);
    }

    public function saveDivisionUsers(): void
    {
        if (!$this->selectedDivisionId) return;

        $divisionId = $this->selectedDivisionId;
        $selected = collect($this->selectedUserIds)
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        // Assign selected users to this division
        if ($selected->isNotEmpty()) {
            User::whereIn('id', $selected)->update(['division_id' => $divisionId]);
        }

        // Unassign users that were in this division but not selected
        User::where('division_id', $divisionId)
            ->whereNotIn('id', $selected->all())
            ->update(['division_id' => null]);

        session()->flash('success', 'Anggota divisi berhasil diperbarui');
        $this->closeUsersModal();
        $this->dispatch('division-users-updated');
    }

    #[Title("Master Data - Divisi")]
    public function render()
    {
        return view('livewire.admin.master-data.divisions', [
            'divisions' => Division::withCount('users')->orderBy('name')->get(),
            'allUsers' => User::orderBy('name')->get(['id','name','email','division_id']),
        ]);
    }
}
