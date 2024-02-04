<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Device;


class DatabaseSeeder extends Seeder
{
    protected $model = Device::class;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            SettingsSeeder::class,
            PermissionSeeder::class,
        ]);

        \Database\Factories\DeviceFactory::times(10)->create();
        \Database\Factories\GroupFactory::times(10)->create();
    }
}
