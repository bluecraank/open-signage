<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'username',
        'action',
    ];

    // The column holds 255 characters, a long name or setting value must not abort the request
    public function setActionAttribute($value): void
    {
        $this->attributes['action'] = Str::limit((string) $value, 252);
    }
}
