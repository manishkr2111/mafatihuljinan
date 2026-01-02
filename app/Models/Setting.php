<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['setting_key', 'setting_value'];

    // Helper to get a setting by key
    public static function get($key, $default = null)
    {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    }

    // Helper to set/update a setting
    public static function set($key, $value)
    {
        return static::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
    }
}
