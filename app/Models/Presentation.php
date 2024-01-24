<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Presentation extends Model
{
    protected static function booted(): void
    {
        static::created(function (Presentation $presentation) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.presentation_created', ['name' => $presentation->name]),
            ]);
        });

        static::deleted(function (Presentation $presentation) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.presentation_deleted', ['name' => $presentation->name]),
            ]);
        });
    }

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'description',
        'author',
        'processed',
        'total_slides'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function schedules()
    {
        $upcomingSchedules = Schedule::where('start_time', '>', now())->orderBy('start_time', 'asc')->get();
        $activeSchedules = Schedule::where('start_time', '<', now())->where('end_time', '>', now())->orderBy('start_time', 'asc')->get();

        $schedules = $upcomingSchedules->merge($activeSchedules);
        $schedules = $schedules->where('presentation_id', $this->id);

        return $schedules;
    }
}
