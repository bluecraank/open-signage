<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    public static function get($key)
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        }

        return null;
    }

    static function getLoadingText($device) {
        $text = Setting::get('LOADING_BACKGROUND_TEXT');

        $text = str_replace("{hostname}", $device->name, $text);
        $text = str_replace("{location}", $device->description, $text);

        return $text;
    }
}
