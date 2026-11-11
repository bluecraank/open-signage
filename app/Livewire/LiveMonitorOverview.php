<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\Presentation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Component;

class LiveMonitorOverview extends Component
{
    // Remember the selected sort order across page loads
    #[Session]
    public $sort_by = 'name';

    #[Locked]
    public $sortToColumn = [
        'name' => 'name',
        'group' => 'group_id',
        'status' => 'active',
        'updated' => 'updated_at',
        'presentation' => 'presentation_id',
    ];

    public function render()
    {
        if (!array_key_exists($this->sort_by, $this->sortToColumn)) {
            $this->sort_by = 'name';
        }

        $devices = Device::all()->sortBy($this->sortToColumn[$this->sort_by]);

        $presentation = Presentation::all()->keyBy('id')->toArray();

        return view('livewire.live-monitor-overview', ['devices' => $devices, 'presentation' => $presentation]);
    }
}
