<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $casts = ['notifications' => 'array'];
    protected $fillable = ["id"];

    // Always work with a single settings row (id = 1), creating it on first use.
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}