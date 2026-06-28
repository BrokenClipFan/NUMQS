<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverQueue extends Model
{
    protected $fillable = [
        'driver_profile_id',
        'terminal_id',
        'queued_at',
        'filling_at',
        'position',
    ];
}
