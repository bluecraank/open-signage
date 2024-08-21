<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected static function booted(): void
    {
        static::created(function (Group $group) {

            $ip = request()->ip();
            // If HTTP_X_FORWARDED_FOR is set, use that instead
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            Log::create([
                'ip_address' => $ip,
                'username' => Auth::user()->name ?? 'System',
                'action' => __('log.group_created', ['name' => $group->name]),
            ]);
        });

        static::deleted(function (Group $group) {

            $ip = request()->ip();
            // If HTTP_X_FORWARDED_FOR is set, use that instead
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            Log::create([
                'ip_address' => $ip,
                'username' => Auth::user()->name ?? 'System',
                'action' => __('log.group_deleted', ['name' => $group->name]),
            ]);
        });
    }

    protected $fillable = ['name', 'presentation_id', 'created_by'];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
