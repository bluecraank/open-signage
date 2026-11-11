<?php

namespace App\Livewire;

use App\Models\Device;
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

        $devices = Device::with('group')->get();

        // Refresh the online state first, the counters and the sorting depend on it
        $online = [];
        foreach ($devices as $device) {
            $online[$device->id] = $device->isActive();
        }

        $devices = $devices->sortBy($this->sortToColumn[$this->sort_by]);

        $stats = [
            'total' => $devices->count(),
            'online' => $devices->filter(fn ($d) => $d->registered && $online[$d->id])->count(),
            'offline' => $devices->filter(fn ($d) => $d->registered && !$online[$d->id])->count(),
            'pending' => $devices->where('registered', false)->count(),
        ];

        return view('livewire.live-monitor-overview', compact('devices', 'online', 'stats'));
    }
}
