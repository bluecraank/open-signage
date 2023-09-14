<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Presentation;
use App\Models\Schedule;
use Illuminate\Http\Request;

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
            'name' => 'required|string|min:2',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'devices' => 'nullable|array',
            'groups' => 'nullable|array',
            'presentation_id' => 'required|integer|exists:presentations,id',
        ]);

        if(empty($request->devices) && empty($request->groups)) {
            return redirect()->back()->withErrors(['message' => __('You must select at least one device or group')]);
        }

        // Date to timestamp
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);

        // dd($start_date, $end_date);

        $schedule = Schedule::create([
            'name' => $request->name,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'devices' => $request->devices ?? [],
            'groups' => $request->groups ?? [],
            'presentation_id' => $request->presentation_id,
        ]);

        return redirect()->route('schedules.index')->with('success', __('Schedule created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        //
    }
}
