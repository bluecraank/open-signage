<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Log;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::all()->sortBy('name');
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presentations = Presentation::get();
        $devices = Device::all();
        return view('groups.create', compact('presentations', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups|min:2|max:255',
            'presentation_id' => 'required|exists:presentations,id',
            'devices' => 'nullable|array',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'presentation_id' => $request->presentation_id,
            'created_by' => Auth::user()->name,
        ]);

        if ($request->devices) {
            foreach ($request->devices as $device_id) {
                $device = Device::find($device_id);
                if(!$device) continue;
                $device->group_id = $group->id;
                $device->save();
            }
        }

        return redirect()->route('groups.index')->with('success', __('Group created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::findOrFail($id);
        $devices = Device::all();
        $presentations = Presentation::get();

        return view('groups.show', compact('group', 'devices', 'presentations'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = Group::whereId($id)->first();

        if (!$group) {
            return redirect()->route('groups.index')->with('error', __('Group not found'));
        }

        $request->validate([
            'name' => 'required|min:2|max:255|unique:groups,name,' . $group->id,
            'presentation_id' => 'required|exists:presentations,id',
            'devices' => 'nullable|array',
        ]);

        $group->name = $request->name;
        $group->presentation_id = $request->presentation_id;
        $group->save();

        Device::where('group_id', $group->id)->update(['group_id' => null]);

        if ($request->devices) {
            foreach ($request->devices as $device_id) {
                $device = Device::find($device_id);
                if(!$device) continue;
                $device->group_id = $group->id;
                $device->save();
            }
        }

        $ip = request()->ip();

        // If HTTP_X_FORWARDED_FOR is set, use that instead
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        Log::create([
            'ip_address' => $ip,
            'username' => Auth::user()->name,
            'action' => __('log.group_updated', ['name' => $group->name]),
        ]);

        return redirect()->back()->with('success', __('Group updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::whereId($id)->first();

        if (!$group) {
            return redirect()->route('groups.index')->with('error', __('Group not found'));
        }

        Device::where('group_id', $group->id)->update(['group_id' => null]);

        $group->delete();

        return redirect()->route('groups.index')->with('success', __('Group deleted'));
    }
}
