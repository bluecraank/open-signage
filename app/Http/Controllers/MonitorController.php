<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonitorController extends Controller
{
    public function show($secret)
    {
        $device = Device::where('secret', $secret)->first();

        if(!$device) {
            return view('unassigned', ['error' => "no_device"]);
        }

        if(!$device->registered) {
            return view('unassigned', ['error' => "not_registered"]);
        }

        $presentation = $device->getPresentation();

        if(!$presentation) {
            return view('unassigned', ['error' => "no_pres_assigned"]);
        }

        $slides = $presentation->slides()->orderBy('order')->get();

        if(!$slides) {
            return "<meta http-equiv='refresh' content='1'>".json_encode(['error' => "No slides found."]);
        }

        $images = [];

        foreach($slides as $slide) {
            $images[] = [
                'type' => $slide->type,
                'url' => $slide->publicpath(),
            ];
        }

        $slides = $images;

        return view('monitor', ['slides' => $slides, 'last_update' => $presentation->updated_at->timestamp, 'device' => $device]);
    }

    public function hasUpdate(Request $request) {
        // Trigger schedule check
        ScheduleController::checkForExpiredSchedules();

        $validator = Validator::make(
            $request->all(),
            [
                'secret' => 'required|string',
                'currentSlide' => 'required|integer',
                'last_update' => 'required|integer',
                'startup_timestamp' => 'required|integer',
                'presentation_id' => 'required|integer'
            ]
        );

        if($validator->fails()) {
            return json_encode(['error' => "Invalid request."]);
        }

        $secret = $request->input('secret');

        $device = Device::where('secret', $secret)->first();

        if(!$device) {
            return json_encode(['error' => "Device not found."]);
        }

        $presentation = $device->getPresentation();

        if(!$presentation) {
            return json_encode(['error' => "No presentation assigned to this device."]);
        }

        $datetime = $request->input('startup_timestamp');
        $datetime = date('Y-m-d H:i:s', $datetime);

        $device->current_slide = $request->input('currentSlide');
        $device->startup_timestamp = $datetime;
        $device->touch('last_seen');


        $ip = request()->ip();

        // If HTTP_X_FORWARDED_FOR is set, use that instead
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $device->ip_address = $ip;

        $force_reload = $device->force_reload;
        if($device->force_reload) {
            $device->force_reload = false;
        }

        $device->save();

        $return = [
            'error' => false,
            'last_update' => $presentation->updated_at->timestamp,
            'presentation_id' => $presentation->id,
            'force_reload' => $force_reload,
        ];

        return json_encode($return);
    }
}
