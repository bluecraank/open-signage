<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

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
            'MONITOR_CHECK_UPDATE_TIME_SECONDS' => 'required|numeric|min:1',
            'SLIDE_IN_TIME_MS' => 'required|numeric|min:100',
            'SLIDE_OUT_TIME_MS' => 'required|numeric|min:100',
            'INTERVAL_NEXT_SLIDE_MS' => 'required|numeric|min:1000',
            'LOADING_BACKGROUND_TEXT' => 'nullable|string',
            'LOADING_BACKGROUND_TYPE' => 'required|string|in:image,color',
            'LOADING_BACKGROUND_COLOR' => 'required|string|starts_with:#|size:7',
            'LOADING_BACKGROUND_IMAGE' => 'required|string|starts_with:https://,http://|url',
        ]);

        $settings = Setting::all()->keyBy('key')->toArray();

        foreach ($request->all() as $key => $value) {
            if (isset($settings[$key])) {
                $settings[$key]['value'] = $value;
            }
        }

        foreach ($settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value['value']]);
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
