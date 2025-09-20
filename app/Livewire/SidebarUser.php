<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SidebarUser extends Component
{
    public ?User $user = null;

    protected $listeners = [
        'avatar-updated' => 'refreshUser',
        'profile-updated' => 'refreshUser',
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function refreshUser(): void
    {
        $this->user?->refresh();
    }

    public function render()
    {
        return view('livewire.sidebar-user');
    }
}

