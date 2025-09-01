<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Menus extends Component
{
    public $menus;

    public function mount()
    {
        // Fetch menus for the authenticated user's role
        $this->menus = DB::table('menus')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('parent_id')
            ->toArray();

        // Organize into root and children
        $this->menus = [
            'root' => $this->menus[null] ?? [],
            'children' => array_filter($this->menus, fn($key) => !is_null($key), ARRAY_FILTER_USE_KEY),
        ];
    }

    public function render()
    {
        return view('livewire.menus');
    }
}