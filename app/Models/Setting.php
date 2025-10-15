<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
        } catch (Throwable $exception) {
            if (app()->runningUnitTests()) {
                return $default;
            }

            throw $exception;
        }

        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
