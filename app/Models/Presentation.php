<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Presentation extends Model
{
    protected static function booted(): void
    {
        static::created(function (Presentation $presentation) {
            $ip = request()->ip();

            // If HTTP_X_FORWARDED_FOR is set, use that instead
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            Log::create([
                'ip_address' => $ip,
                'username' => Auth::user()->name,
                'action' => __('log.presentation_created', ['name' => $presentation->name]),
            ]);
        });

        static::deleted(function (Presentation $presentation) {
            $ip = request()->ip();

            // If HTTP_X_FORWARDED_FOR is set, use that instead
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            Log::create([
                'ip_address' => $ip,
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
        return $this->hasMany(Schedule::class);
    }

    public function getSchedules()
    {
        $upcomingSchedules = Schedule::where('start_time', '>', now())->orderBy('start_time', 'asc')->get();
        $activeSchedules = Schedule::where('start_time', '<', now())->where('end_time', '>', now())->orderBy('start_time', 'asc')->get();

        $schedules = $upcomingSchedules->merge($activeSchedules);
        $schedules = $schedules->where('presentation_id', $this->id);

        return $schedules;
    }

    public function in_use()
    {
        return $this->devices->count() > 0 || $this->groups->count() > 0 || $this->getSchedules()->count() > 0;
    }
}
