<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    protected $fillable = ['cle', 'valeur', 'groupe', 'description'];

    public static function get(string $cle, $default = null)
    {
        $setting = static::where('cle', $cle)->first();
        return $setting ? $setting->valeur : $default;
    }
}