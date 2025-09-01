<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['name' => 'Dashboard', 'icon' => 'feathericon-home', 'route' => '/dashboard', 'order' => 1],
            ['name' => 'Sistem Audit & Pengawasan', 'icon' => 'audit', 'route' => null, 'order' => 2],
            ['name' => 'Sistem Administrasi Operasional', 'icon' => 'admin-ops', 'route' => null, 'order' => 3, 'parent_id' => null],
            ['name' => 'Manajemen Konsumsi', 'icon' => '', 'route' => '/consumption', 'order' => 1, 'parent_id' => 3],
            ['name' => 'Manajemen Pemeliharaan', 'icon' => '', 'route' => '/maintenance', 'order' => 2, 'parent_id' => 3],
            ['name' => 'Manajemen Agenda', 'icon' => '', 'route' => '/agenda', 'order' => 3, 'parent_id' => 3],
            ['name' => 'Percakapan', 'icon' => 'chat', 'route' => '/chat', 'order' => 4],
            ['name' => 'Manajemen User', 'icon' => 'feathericon-user', 'route' => null, 'order' => 5],
            ['name' => 'Role', 'icon' => '', 'route' => '/user-management', 'order' => 1, 'parent_id' => 8],
            ['name' => 'User', 'icon' => '', 'route' => '/role-management', 'order' => 2, 'parent_id' => 8],
            ['name' => 'Log Activity', 'icon' => 'log', 'route' => '/logs', 'order' => 6],
            ['name' => 'Settings', 'icon' => 'feathericon-settings', 'route' => '/settings', 'order' => 7],
            ['name' => 'Logout', 'icon' => 'feathericon-log-out', 'route' => '/logout', 'order' => 8],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->insert([
                'name' => $menu['name'],
                'icon' => $menu['icon'] ?: null,
                'route' => $menu['route'] ?: null,
                'order' => $menu['order'],
                'parent_id' => isset($menu['parent_id']) ? $menu['parent_id'] : null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
