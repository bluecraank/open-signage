<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Presentation;
use Livewire\Component;

class LiveMonitorOverview extends Component
{
    public $sort_by = 'name';

    public $sortToColumn = [
        'name' => 'name',
        'group' => 'group_id',
        'status' => 'active',
        'updated' => 'updated_at',
        'presentation' => 'presentation_id',
    ];

    public function render()
    {
        $validSorts = ['name', 'group', 'status', 'updated', 'presentation'];

        if (!in_array($this->sort_by, $validSorts)) {
            $this->sort_by = 'name';
        }

        $devices = Device::all()->sortBy($this->sortToColumn[$this->sort_by]);



        $presentation = Presentation::all()->keyBy('id')->toArray();

        return view('livewire.live-monitor-overview', ['devices' => $devices, 'presentation' => $presentation]);
    }

    public function sortBy()
    {
        return true;
    }
}
