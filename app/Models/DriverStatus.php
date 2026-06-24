<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverStatus extends Model
{
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'dispatched_to',
        'state',
        'last_updated',
    ];
}
