<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'name_on_disk',
        'presentation_id',
        'order',
        'type'
    ];

    protected $appends = ['publicpreviewpath', 'publicpath'];

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    public function getPublicpreviewpathAttribute() {
        return $this->publicpreviewpath();
    }

    public function publicpreviewpath() {
        // Deprecated

        return asset('data/presentations/' . $this->presentation_id . '/' . $this->name_on_disk);
    }

    public function getPublicpathAttribute() {
        return $this->publicpath();
    }

    public function publicpath() {
        return asset('data/presentations/' . $this->presentation_id . '/' . $this->name_on_disk);
    }
}
