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

        return redirect()->route('devices.index');
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

        return redirect()->route('devices.monitor', ['id' => $device->id]);
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
            return redirect()->route('devices.index');
        }

        if($request->has('reload')) {
            $device->force_reload = true;
            $device->save();
            return redirect()->back()->with('success', __('Device will reload on next update'));
        }

        $name = $request->input('name');
        $description = $request->input('description');
        // $ip_address = $request->input('ip_address');
        $presentation_id = $request->input('presentation_id');

        if($presentation_id == 0) {
            $presentation_id = null;
        }

        $device->name = $name;
        $device->description = $description;
        // $device->ip_address = $ip_address;
        $device->presentation_id = $presentation_id;

        $device->save();

        return redirect()->route('devices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $device = Device::where('id', $id)->first();

        if(!$device) {
            return redirect()->route('devices.index');
        }

        $device->delete();

        return redirect()->route('devices.index');
    }
}
