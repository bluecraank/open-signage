<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function show($id)
    {
        $device = Device::whereId($id)->first();

        if(!$device) {
            return "Device not found.";
        }

        if(!$device->registered) {
            return "Device not registered.";
        }

        $presentation = $device->presentation;

        if(!$presentation) {
            return "No presentation assigned to this device.";
        }

        $slides = $presentation->slides()->orderBy('order')->get();

        if(!$slides) {
            return "No slides found for this presentation.";
        }

        $images = [];
        foreach($slides as $slide) {
            $images[] = $slide->publicpath();
        }

        $slides = $images;

        return view('monitor', ['slides' => $slides, 'last_update' => $presentation->updated_at->timestamp, 'device' => $device]);
    }

    public function hasUpdate($id) {
        $device = Device::whereId($id)->first();

        if(!$device) {
            return "Device not found.";
        }

        $presentation = $device->presentation;

        if(!$presentation) {
            return "No presentation assigned to this device.";
        }

        $device->touch('last_seen');

        $return = [
            'last_update' => $presentation->updated_at->timestamp,
            'presentation_id' => $presentation->id,
        ];

        return json_encode($return);
    }
}
