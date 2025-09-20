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
        // Create or update base menus according to requested structure
        // All routes use named routes to be compatible with the menus component.

        // Root: Dashboard
        $dashboardId = $this->upsertMenu('Dashboard', null, 'dashboard', 1);

        // Root: Sistem Audit & Pengawasan (klik ke halaman buat audit)
        $sapId = $this->upsertMenu('Sistem Audit & Pengawasan', null, 'dashboard.audit.create', 2);

        // Root: Sistem Administrasi Operasional (parent)
        $saoId = $this->upsertMenu('Sistem Administrasi Operasional', null, null, 3);

        // Children under SAO (use create pages)
        $this->upsertMenu('Manajemen Konsumsi', $saoId, 'dashboard.consumption.create', 1);
        $this->upsertMenu('Manajemen Pemeliharaan', $saoId, 'dashboard.maintenance.create', 2);
        $this->upsertMenu('Manajemen Agenda', $saoId, 'dashboard.agenda.create', 3);

        // Root: Percakapan
        $chatId = $this->upsertMenu('Percakapan', null, 'dashboard.chat', 4);
        // Settings page for user profile
        $settingsId = $this->upsertMenu('Setting', null, 'dashboard.settings', 90, 'feathericon-settings');
        // Root: Logout (special handling in menus view renders POST form)
        $logoutId = $this->upsertMenu('Logout', null, 'logout', 99, 'feathericon-log-out');

        // Assign these menus to 'user' role
        $userRoleId = DB::table('roles')->where('name', 'user')->value('id');
        if ($userRoleId) {
            $menuIds = DB::table('menus')
                ->whereIn('name', [
                    'Dashboard',
                    'Sistem Audit & Pengawasan',
                    'Sistem Administrasi Operasional',
                    'Manajemen Konsumsi',
                    'Manajemen Pemeliharaan',
                    'Manajemen Agenda',
                    'Percakapan',
                    'Setting',
                    'Logout',
                ])->pluck('id');

            foreach ($menuIds as $menuId) {
                $exists = DB::table('menu_roles')
                    ->where('menu_id', $menuId)
                    ->where('role_id', $userRoleId)
                    ->exists();
                if (!$exists) {
                    DB::table('menu_roles')->insert([
                        'menu_id' => $menuId,
                        'role_id' => $userRoleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function upsertMenu(string $name, ?int $parentId, ?string $route, int $order, ?string $icon = null): int
    {
        $exists = DB::table('menus')->where('name', $name)->first();
        if ($exists) {
            DB::table('menus')->where('id', $exists->id)->update([
                'icon' => $icon,
                'route' => $route,
                'parent_id' => $parentId,
                'order' => $order,
                'is_active' => true,
                'updated_at' => now(),
            ]);
            return (int) $exists->id;
        }

        return (int) DB::table('menus')->insertGetId([
            'name' => $name,
            'icon' => $icon,
            'route' => $route,
            'parent_id' => $parentId,
            'order' => $order,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
