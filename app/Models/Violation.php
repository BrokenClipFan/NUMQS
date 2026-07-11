<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'driver_profile_id',
        'type',
        'name',
        'location',
        'severity',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}
