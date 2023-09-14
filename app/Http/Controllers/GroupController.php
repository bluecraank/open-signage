<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Presentation;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $presentations = Presentation::all();
        $devices = Device::all();
        return view('groups.create', compact('presentations', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups',
            'presentation_id' => 'required|exists:presentations,id',
            'devices' => 'nullable|array',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'presentation_id' => $request->presentation_id,
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
        //
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
        //
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

        $group->delete();

        return redirect()->route('groups.index')->with('success', __('Group deleted'));
    }
}
