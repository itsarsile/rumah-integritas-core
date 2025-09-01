<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $getAdminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
       $menuIds = DB::table('menus')->pluck('id');
       $menuRoleData = [];
       foreach ($menuIds as $menuId) {
           $menuRoleData[] = [
               'menu_id' => $menuId,
               'role_id' => $getAdminRoleId,
               'created_at' => now(),
               'updated_at' => now(),
           ];
       }
       DB::table('menu_roles')->insert($menuRoleData); 
    }
}
