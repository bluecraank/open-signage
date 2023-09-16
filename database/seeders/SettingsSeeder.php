<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::firstOrCreate([
            'key' => 'MONITOR_REFRESH_TIME_SECONDS'
        ], [
            'value' => '43200'
        ]);

        Setting::firstOrCreate([
            'key' => 'MONITOR_CHECK_UPDATE_TIME_SECONDS'
        ], [
            'value' => '30'
        ]);

        Setting::firstOrCreate([
            'key' => 'SLIDE_IN_TIME_MS'
        ], [
            'value' => '1100'
        ]);

        Setting::firstOrCreate([
            'key' => 'SLIDE_OUT_TIME_MS'
        ], [
            'value' => '1600'
        ]);

        Setting::firstOrCreate([
            'key' => 'INTERVAL_NEXT_SLIDE_MS'
        ], [
            'value' => '20000'
        ]);

        Setting::firstOrCreate([
            'key' => 'LOADING_BACKGROUND_TEXT'
        ], [
            'value' => ''
        ]);

        Setting::firstOrCreate([
            'key' => 'LOADING_BACKGROUND_COLOR'
        ], [
            'value' => '#000000'
        ]);

        Setting::firstOrCreate([
            'key' => 'LOADING_BACKGROUND_TYPE'
        ], [
            'value' => 'image'
        ]);

        Setting::firstOrCreate([
            'key' => 'LOADING_BACKGROUND_IMAGE'
        ], [
            'value' => 'https://picsum.photos/1920/1080'
        ]);
    }
}
