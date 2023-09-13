<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create devices', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'read devices', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update devices', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete devices', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'create presentations', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'read presentations', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'update presentations', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'delete presentations', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'force reload monitor', 'guard_name' => 'sanctum']);
        Permission::create(['name' => 'assign presentations', 'guard_name' => 'sanctum']);

        Role::create(['name' => 'admin', 'guard_name' => 'sanctum'])
            ->givePermissionTo(Permission::all());

        Role::create(['name' => 'user', 'guard_name' => 'sanctum'])
            ->givePermissionTo([
                'create devices',
                'read devices',
                'create presentations',
                'read presentations',
                'update presentations',
                'delete presentations',
                'force reload monitor',
                'assign presentations',
            ]);
    }
}
