<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Presentation;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presentations = Presentation::all();
        return view('devices.create', compact('presentations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'name' => 'required|min:2',
            'description' => 'required|min:2',
            'presentation_id' => 'nullable|integer',
        ]);

        $name = $request->input('name');
        $description = $request->input('description');
        $ip_address = $request->input('ip_address');
        $presentation_id = $request->input('presentation_id');

        if($presentation_id == 0) {
            $presentation_id = null;
        }

        $secret = bin2hex(random_bytes(16));

        Device::create([
            'name' => $name,
            'description' => $description,
            'ip_address' => $ip_address,
            'presentation_id' => $presentation_id,
            'secret' => $secret,
        ]);

        return redirect()->route('devices.index')->with('success', __('Device created'));
    }

    public function register(Request $request)
    {
        $secret = $request->input('secret');

        $device = Device::where('secret', $secret)->first();

        if(!$device) {
           abort(404);
        }

        if(!config('app.debug')) {
            if($device->ip_address != $request->ip()) {
                abort(403);
            }
        }

        $device->active = true;
        $device->registered = true;

        $device->save();

        return redirect()->route('devices.monitor', ['secret' => $device->secret]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $device = Device::where('id', $id)->first();

        if(!$device) {
            return redirect()->route('devices.index');
        }

        $presentations = Presentation::all();

        return view('devices.show', compact('device', 'presentations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $device = Device::where('id', $id)->first();

        if(!$device) {
            return redirect()->back()->withErrors(['message' => __('Device not found')]);
        }

        $name = $request->input('name');
        $description = $request->input('description');
        $presentation_id = $request->input('presentation_id');

        if($presentation_id == 0) {
            $presentation_id = null;
        }

        $device->name = $name;
        $device->description = $description;

        if($presentation_id != $device->presentation_id) {
            $device->current_slide = 0;
        }

        $device->presentation_id = $presentation_id;

        $device->save();

        return redirect()->back()->with('success', __('Device updated'));
    }

    public function force_reload(Request $request, string $id)
    {
        $device = Device::where('id', $id)->first();

        if(!$device) {
            return redirect()->back()->withErrors(['message' => __('Device not found')]);
        }

        $device->force_reload = true;
        $device->save();

        return redirect()->back()->with('success', __('Device will reload on next update'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $device = Device::where('id', $id)->first();

        if(!$device) {
            return redirect()->route('devices.index')->withErrors(['message' => __('Device not found')]);
        }

        $device->delete();

        return redirect()->route('devices.index')->with('success', __('Device deleted'));
    }
}
