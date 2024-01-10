<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'MONITOR_REFRESH_TIME_SECONDS' => 'required|numeric|min:1',
            // 'MONITOR_CHECK_UPDATE_TIME_SECONDS' => 'required|numeric|min:1',
            'SLIDE_IN_TIME_MS' => 'required|numeric|min:100',
            'SLIDE_OUT_TIME_MS' => 'required|numeric|min:100',
            'INTERVAL_NEXT_SLIDE_MS' => 'required|numeric|min:1000',
            'LOADING_BACKGROUND_TEXT' => 'nullable|string',
            'LOADING_BACKGROUND_TYPE' => 'required|string|in:image,color',
            'LOADING_BACKGROUND_COLOR' => 'required|string|starts_with:#|size:7',
            'LOADING_BACKGROUND_IMAGE' => 'required|string|starts_with:https://,http://|url',
        ]);

        $settings = Setting::all()->keyBy('key')->toArray();

        $updatedSettings = [];

        foreach ($request->all() as $key => $value) {
            if (isset($settings[$key]) && $settings[$key]['value'] != $value) {
                $updatedSettings[$key]['value'] = $value;
            }
        }

        $forceReload = false;
        foreach ($updatedSettings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value['value']]);

            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.setting_updated', ['name' => $key, 'value' => $value['value']]),
            ]);

            $forceReload = true;
        }

        if($forceReload) {
            Device::all()->each(function ($device) {
                $device->force_reload = true;
                $device->save();
            });
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
