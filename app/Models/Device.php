<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Device extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;


    protected static function booted(): void
    {
        static::created(function (Device $device) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()?->name ?? 'System',
                'action' => __('log.device_created', ['name' => $device->name]),
            ]);
        });

        static::deleted(function (Device $device) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.device_deleted', ['name' => $device->name]),
            ]);
        });
    }

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'description',
        'secret',
        'ip_address',
        'presentation_id',
        'group_id',
        'registered',
        'active',
    ];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getPresentation() {
        if($this->activeSchedule()) {
            return $this->activeSchedule()->presentation;
        }

        if($this->group_id) {
            return $this->group->presentation;
        }

        if($this->presentation_id) {
            return $this->presentation;
        }

        return null;
    }

    public function getPresentationId() {

        if($this->activeSchedule()) {
            return $this->activeSchedule()->presentation_id;
        }

        if($this->group_id) {
            $group = $this->group;

            if(!$group) {
                $this->group_id = null;
                $this->save();
            }

            return $group?->presentation_id;
        }

        if($this->presentation_id) {
            return $this->presentation_id;
        }

        return null;
    }

    public function presentationFromGroup() {
        if($this->group?->presentation_id) {
            return true;
        }

        return false;
    }

    public function activeSchedule() {
        $activeSchedule = Schedule::where('start_time', '<', now())->where('end_time', '>', now())->get();

        $assignedOverDevice = null;
        $assignedOverGroup = null;

        foreach($activeSchedule as $schedule) {
            if($schedule->devices) {
                $devices = $schedule->devices;
                if(in_array($this->id, $devices)) {
                    $assignedOverDevice = $schedule;
                }
            }

            if($schedule->groups) {
                $groups = $schedule->groups;
                if(in_array($this->group_id, $groups)) {
                    $assignedOverGroup = $schedule;
                }
            }
        }

        if($assignedOverDevice) {
            return $assignedOverDevice;
        } else if($assignedOverGroup) {
            return $assignedOverGroup;
        }

        return null;
    }

    public function presentationFromSchedule() {
        if($this->activeSchedule()) {
            return true;
        }

        return false;
    }

    public function isActive() {
        $now = new \DateTime();
        $lastSeen = new \DateTime($this->last_seen);
        $this->active = true;

        if(!$this->registered) {
            $this->active = false;
        }

        $diff = $now->diff($lastSeen);
        $refresh_interval = Setting::get('MONITOR_CHECK_UPDATE_TIME_SECONDS');
        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;

        // Days to positive
        if($daysInSecs < 0) {
            $daysInSecs = $daysInSecs * -1;
        }

        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;

        $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
        if ($seconds > $refresh_interval*2.5 || $this->created_at == $this->updated_at) {
            $this->active = false;
        }

        $this->save();

        return $this->active;
    }
}
