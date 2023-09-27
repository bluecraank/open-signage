<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
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

        foreach($activeSchedule as $schedule) {
            if($schedule->devices) {
                $devices = $schedule->devices;
                if(in_array($this->id, $devices)) {
                    return $schedule;
                }
            }

            if($schedule->groups) {
                $groups = $schedule->groups;
                if(in_array($this->group_id, $groups)) {
                    return $schedule;
                }
            }
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

        if(!$this->registered) {
            return '🟠';
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
        if ($seconds > $refresh_interval*2.5) {
            return '🔴';
        }

        return '🟢';
    }
}
