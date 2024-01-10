<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected static function booted(): void
    {
        static::created(function (Group $group) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.group_created', ['name' => $group->name]),
            ]);
        });

        static::deleted(function (Group $group) {
            Log::create([
                'ip_address' => request()->ip(),
                'username' => Auth::user()->name,
                'action' => __('log.group_deleted', ['name' => $group->name]),
            ]);
        });
    }

    protected $fillable = ['name', 'presentation_id'];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
