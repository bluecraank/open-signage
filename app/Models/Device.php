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
        if($this->group_id) {
            return $this->group->presentation;
        }

        if($this->presentation_id) {
            return $this->presentation;
        }

        return null;
    }

    public function getPresentationId() {
        if($this->group_id) {
            return $this->group->presentation_id;
        }

        if($this->presentation_id) {
            return $this->presentation_id;
        }

        return null;
    }

    public function presentationFromGroup() {
        if($this->group->presentation_id) {
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
        $refresh_interval = config('app.monitor_check_update_time_seconds');
        $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        $hoursInSecs = $diff->h * 60 * 60;
        $minsInSecs = $diff->i * 60;

        $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;

        if ($seconds > $refresh_interval*2.5) {
            return '🔴';
        }

        return '🟢';
    }
}
