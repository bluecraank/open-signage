<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Group;
use App\Models\Log;
use App\Models\Presentation;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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
            'del_pres_after_schedule_ends' => 'nullable|in:"on"',
        ]);

        if(empty($request->devices) && empty($request->groups)) {
            return redirect()->back()->withErrors(['message' => __('You must select at least one device or group')]);
        }

        if($request->has('del_pres_after_schedule_ends') && $request->del_pres_after_schedule_ends == 'on') {
            $del_pres_after_schedule_ends = true;
        } else {
            $del_pres_after_schedule_ends = false;
        }

        // Date to timestamp
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);

        Schedule::create([
            'name' => $request->name,
            'start_time' => $start_date,
            'end_time' => $end_date,
            'devices' => $request->devices ?? [],
            'groups' => $request->groups ?? [],
            'presentation_id' => $request->presentation_id,
            'enabled' => true,
            'created_by' => Auth::user()->name ?? 'N/A',
            'delete_presentation' => $del_pres_after_schedule_ends,
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
            'del_pres_after_schedule_ends' => 'nullable|in:"on"',
        ]);

        if(empty($request->devices) && empty($request->groups)) {
            return redirect()->back()->withErrors(['message' => __('You must select at least one device or group')]);
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
            'enabled' => true,
            'delete_presentation' => $request->has('del_pres_after_schedule_ends') && $request->del_pres_after_schedule_ends == 'on' ? true : false,
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

    static function checkForExpiredSchedules() {
        $currentTimestamp = now();

        $expiredSchedules = Schedule::where('end_time', '<', $currentTimestamp)->where('delete_presentation', 1)->get();

        foreach($expiredSchedules as $schedule) {
                $presentation = $schedule->presentation;

                if($presentation) {
                    if($presentation->devices()->count() == 0 && $presentation->groups()->count() == 0) {
                        try {
                            $presentation->slides()->delete();

                            File::delete(storage_path('app/public/presentations/' . $presentation->id . '/' . $presentation->id) . '.pdf');
                            File::deleteDirectory(storage_path('app/public/presentations/' . $presentation->id . '/'));

                            $oldName = $presentation->name;

                            $presentation->delete();

                            Log::create([
                                'ip_address' => "127.0.0.1",
                                'username' => 'System',
                                'action' => __('log.presentation_deleted_because_schedule', ['name' => $oldName, 'schedule' => $schedule->name,]),
                            ]);
                        } catch (\Exception $e) {

                        }
                    } else {
                        Log::create([
                            'ip_address' => "127.0.0.1",
                            'username' => 'System',
                            'action' => __('log.presentation_not_deleted_because_schedule', ['name' => $presentation->name, 'schedule' => $schedule->name,]),
                        ]);
                    }
                }

                // Disable delete_presentation after deleting assigned presentation to avoid double deletion
                $schedule->delete_presentation = false;
                $schedule->save();

                return true;
        }

        return false;
    }
}
