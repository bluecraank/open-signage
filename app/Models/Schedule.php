<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name',
        'presentation_id',
        'devices',
        'groups',
        'start_time',
        'end_time',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'groups' => 'array',
        'devices' => 'array',
    ];

    public function groups()
    {
        $groups = Group::whereIn('id', $this->groups)->get();
        return $groups;
    }

    public function appliesTo() {
        $groups = $this->groups();
        $devices = $this->devices();
        return $devices->count() . ' ' . trans_choice('Device|Devices', $devices->count()) . ', ' . $groups->count() . ' ' . trans_choice('Group|Groups', $groups->count());
    }

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function devices()
    {
        $devices = Device::whereIn('id', $this->devices)->get();
        return $devices;
    }

    public function startDate() {
        $locale = config('app.locale');
        if($locale == 'de') {
            return Carbon::parse($this->start_time)->format('d.m.Y H:i');
        }

        return Carbon::parse($this->start_time)->format('Y-m-d H:i');
    }

    public function endDate() {
        $locale = config('app.locale');
        if($locale == 'de') {
            return Carbon::parse($this->start_time)->format('d.m.Y H:i');
        }

        return Carbon::parse($this->end_time)->format('Y-m-d H:i');
    }
}
