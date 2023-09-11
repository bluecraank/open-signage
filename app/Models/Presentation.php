<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'description',
        'author',
        'processed',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }
}
