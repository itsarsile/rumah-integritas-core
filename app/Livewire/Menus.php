<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Debugbar;

class Menus extends Component
{
    public $menus;

    public function mount()
    {
        $roleIds = auth()->user()->roles->pluck('id');
        $menus = DB::table('menus')
            ->where('menus.is_active', true)
            ->where(function ($q) use ($roleIds) {
                $q->whereIn('menus.id', function ($sub) use ($roleIds) {
                    $sub->select('menu_id')
                        ->from('menu_roles')
                        ->whereIn('role_id', $roleIds);
                })
                    ->orWhereIn('menus.id', function ($sub) use ($roleIds) {
                        // fetch parents of menus that the role has
                        $sub->select('parent_id')
                            ->from('menus')
                            ->join('menu_roles', 'menus.id', '=', 'menu_roles.menu_id')
                            ->whereIn('menu_roles.role_id', $roleIds)
                            ->whereNotNull('parent_id');
                    });
            })
            ->orderBy('menus.order')
            ->get()
            ->filter(fn($menu) => !$menu->route || Route::has($menu->route));
            
        $grouped = $menus->groupBy('parent_id');

        // Organize into root and children
        $this->menus = [
            'root' => $grouped->get(null, collect()), // root menus (no parent)
            'children' => $grouped->except(null),         // child menus
        ];
        Debugbar::info('fetched menus', $this->menus);
    }

    public function render()
    {
        return view('livewire.menus');
    }
}
