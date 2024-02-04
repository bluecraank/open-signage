<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Log;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all()->sortBy('name');
        $presentation = Presentation::all()->keyBy('id')->toArray();
        return view('devices.index', ['devices' => $devices, 'presentation' => $presentation]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presentations = Presentation::where('processed', true)->get();
        $groups = Group::all();
        return view('devices.create', compact('presentations', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'required|min:2|max:255',
            'presentation_id' => 'nullable|integer',
            'group_id' => 'nullable|integer',
        ]);

        $ip_address = "127.0.0.1";
        $name = $request->input('name');
        $description = $request->input('description');
        $presentation_id = $request->input('presentation_id');
        $group_id = $request->input('group_id');

        if($presentation_id == 0) {
            $presentation_id = null;
        }

        if($group_id == 0) {
            $group_id = null;
        }

        $secret = bin2hex(random_bytes(4));

        Device::create([
            'name' => $name,
            'description' => $description,
            'ip_address' => $ip_address,
            'presentation_id' => $presentation_id,
            'secret' => $secret,
            'group_id' => $group_id,
        ]);

        return redirect()->route('devices.index')->with('success', __('Device created'));
    }

    public function register(Request $request)
    {
        $id = $request->input('id');

        $device = Device::whereId($id)->first();

        if(!$device) {
           return redirect()->route('devices.index')->withErrors(['message' => __('Something went wrong!')]);
        }

        $device->active = true;
        $device->registered = true;

        Log::create([
            'username' => Auth::user()->name,
            'ip_address' => request()->ip(),
            'action' => __('log.device_registered', ['name' => $device->name]),
        ]);

        $device->save();

        return redirect()->back()->with('success', __('Device successfully registered'));
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

        $presentations = Presentation::get();

        return view('devices.show', compact('device', 'presentations'));
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

        $request->validate([
            'name' => 'required|min:2|max:255|unique:devices,name,' . $device->id . ',id',
            'description' => 'required|min:2|max:255',
            'presentation_id' => 'nullable|integer',
        ]);

        $name = $request->input('name');
        $description = $request->input('description');

        if(!$device->presentationFromGroup()) {
            $presentation_id = $request->input('presentation_id');

            if($presentation_id == 0 || $presentation_id == null) {
                $presentation_id = null;
            }

            if($presentation_id != $device->getPresentationId()) {
                $device->current_slide = 0;
            }

            $device->presentation_id = $presentation_id;
        }

        $device->name = $name;
        $device->description = $description;

        Log::create([
            'ip_address' => request()->ip(),
            'username' => Auth::user()->name,
            'action' => __('log.device_updated', ['name' => $device->name]),
        ]);

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

        Log::create([
            'username' => Auth::user()->name,
            'ip_address' => request()->ip(),
            'action' => __('log.device_force_reload', ['name' => $device->name]),
        ]);

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

    public function discover(Request $request) {
        $ip = request()->ip();

        $device = Device::where('ip_address', $ip)->first();

        if($device) {
            return redirect()->route('devices.monitor', ['secret' => $device->secret]);
        }

        // Create monitor
        $secret = bin2hex(random_bytes(4));
        Device::create([
            'name' => "Unknown monitor",
            'description' => $ip,
            'ip_address' => $ip,
            'presentation_id' => null,
            'secret' => $secret,
            'group_id' => null,
            'active' => true,
            'registered' => false,
        ]);

        return redirect()->route('devices.monitor', ['secret' => $secret]);
    }

    static function getActiveInactiveDevices() {
        $active = Device::where('active', true)->count();
        $inactive = Device::where('active', false)->count();

        return ['active' => $active, 'inactive' => $inactive];
    }
}
