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
}
