<?php

namespace Database\Seeders;

use Database\Factories\DeviceFactory;
use Database\Factories\GroupFactory;
use Illuminate\Database\Seeder;

/**
 * Fake devices and groups for development (needs the dev dependency fakerphp/faker).
 * Run with: php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DeviceFactory::times(10)->create();
        GroupFactory::times(10)->create();
    }
}
