<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'create report']);
        Permission::firstOrCreate(['name' => 'update report']);
        Permission::firstOrCreate(['name' => 'delete report']);
        Permission::firstOrCreate(['name' => 'view report']);

        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        $roleUser = Role::create(['name' => 'user']);
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleSupervisor = Role::create(['name' => 'supervisor']);
        $roleOPD = Role::create(['name' => 'opd']);

        $superAdmin = User::create(
            [
                'email' => 'superadmin@example.com',
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ],
        );

        $superAdmin->assignRole($roleSuperAdmin);

        $user = User::create(
            [
                'email' => 'user@example.com',
                'name' => 'User',
                'password' => bcrypt('password'),
            ],
        );

        $user->assignRole($roleUser);

        $admin = User::create(
            [
                'email' => 'admin@example.com',
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ],
        );

        $admin->assignRole($roleAdmin);

        $supervisor = User::create(
            [
                'email' => 'supervisor@example.com',
                'name' => 'Supervisor',
                'password' => bcrypt('password'),
            ],
        );

        $supervisor->assignRole($roleSupervisor);

        $opd = User::create(
            [
                'email' => 'opd@example.com',
                'name' => 'OPD',
                'password' => bcrypt('password'),
            ],
        );

        $opd->assignRole($roleOPD);
    }
}
