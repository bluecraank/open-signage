<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::updateOrCreate(['name' => 'create devices', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read devices', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'update devices', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete devices', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'create presentations', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read presentations', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'update presentations', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete presentations', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'force reload monitor', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'assign presentations', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete slides', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'create users', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read users', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'update users', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete users', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'create groups', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read groups', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'update groups', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete groups', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'assign device to group', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'register devices', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'create schedules', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read schedules', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'update schedules', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'delete schedules', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'manage settings', 'guard_name' => 'sanctum']);
        Permission::updateOrCreate(['name' => 'read logs', 'guard_name' => 'sanctum']);


        Role::updateOrCreate(['name' => 'Super Administrator', 'guard_name' => 'sanctum'])
            ->givePermissionTo(Permission::all());

        Role::updateOrCreate(['name' => 'Supporter', 'guard_name' => 'sanctum'])
            ->givePermissionTo([
                'read devices',
                'read presentations',
                'read groups',
                'force reload monitor',
                'register devices',
                'read logs',
                'read schedules'
            ]);

        Role::updateOrCreate(['name' => 'Manager', 'guard_name' => 'sanctum'])
            ->givePermissionTo([
                'update devices',
                'read devices',
                'create presentations',
                'read presentations',
                'update presentations',
                'delete presentations',
                'force reload monitor',
                'assign presentations',
                'delete slides',
                'create groups',
                'read groups',
                'update groups',
                'delete groups',
                'assign device to group',
                'create schedules',
                'read schedules',
                'update schedules',
                'delete schedules',
            ]);

        Role::updateOrCreate(['name' => 'User', 'guard_name' => 'sanctum'])
            ->givePermissionTo([
                'read devices',
            ]);


        // Assign Super Administrator role to user with ID 1
        $user = User::whereId(1)->first();
        if($user) {
            if(count($user->getRoleNames()) == 0) {
                $user->assignRole('Super Administrator');
            }
        }
    }
}
