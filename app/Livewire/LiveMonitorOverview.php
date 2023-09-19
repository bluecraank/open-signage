<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Presentation;
use Livewire\Component;

class LiveMonitorOverview extends Component
{
    public function render()
    {
        $devices = Device::all();
        $presentation = Presentation::all()->keyBy('id')->toArray();

        return view('livewire.live-monitor-overview', ['devices' => $devices, 'presentation' => $presentation]);
    }
}
