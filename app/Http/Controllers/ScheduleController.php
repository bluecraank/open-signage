<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Presentation;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $upcomingSchedules = Schedule::where('start_time', '>', now())->orderBy('start_time', 'asc')->get();
        $activeSchedules = Schedule::where('start_time', '<', now())->where('end_time', '>', now())->orderBy('start_time', 'asc')->take(10)->get();
        $pastSchedules = Schedule::where('end_time', '<', now())->orderBy('end_time', 'desc')->take(10)->get();

        return view('schedules.index', compact('upcomingSchedules', 'activeSchedules', 'pastSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all();
        $groups = Group::all();
        $presentations = Presentation::all();
        return view('schedules.create', compact('devices', 'groups', 'presentations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'devices' => 'nullable|array',
            'groups' => 'nullable|array',
            'presentation_id' => 'required|integer|exists:presentations,id',
        ]);

        if(empty($request->devices) && empty($request->groups)) {
            return redirect()->back()->withErrors(['message' => __('You must select at least one device or group')]);
        }

        $enabled = true;
        // if($request->has('submit_without_enable')) {
        //     $enabled = false;
        // }

        // Date to timestamp
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);

        $schedule = Schedule::create([
            'name' => $request->name,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'devices' => $request->devices ?? [],
            'groups' => $request->groups ?? [],
            'presentation_id' => $request->presentation_id,
            'enabled' => $enabled,
            'created_by' => Auth::user()->name ?? 'N/A'
        ]);

        return redirect()->route('schedules.index')->with('success', __('Schedule created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::whereId($id)->first();

        if(!$schedule) {
            return redirect()->route('schedules.index')->withErrors(['message' => __('Schedule not found')]);
        }

        $presentations = Presentation::all();
        $groups = Group::all();
        $devices = Device::all();

        return view('schedules.show', compact('schedule', 'presentations', 'groups', 'devices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::whereId($id)->first();

        if(!$schedule) {
            return redirect()->route('schedules.index')->withErrors(['message' => __('Schedule not found')]);
        }

        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'devices' => 'nullable|array',
            'groups' => 'nullable|array',
            'presentation_id' => 'required|integer|exists:presentations,id',
        ]);

        if(empty($request->devices) && empty($request->groups)) {
            return redirect()->back()->withErrors(['message' => __('You must select at least one device or group')]);
        }

        $enabled = false;
        if($request->has('enabled') && $request->enabled == 'on') {
            $enabled = true;
        }

        // Date to timestamp
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);

        $schedule->update(
        [
            'name' => $request->name,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'devices' => $request->devices ?? [],
            'groups' => $request->groups ?? [],
            'presentation_id' => $request->presentation_id,
            'enabled' => $enabled,
        ]);

        return redirect()->route('schedules.index')->with('success', __('Schedule updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::whereId($id)->first();

        if(!$schedule) {
            return redirect()->route('schedules.index')->withErrors(['message' => __('Schedule not found')]);
        }

        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', __('Schedule deleted'));
    }
}
