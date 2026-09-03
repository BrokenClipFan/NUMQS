<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DriverProfile;

class Violation extends Model
{
    protected $fillable = [
        'driver_profile_id',
        'type',
        'name',
        'location',
        'severity',
        'properties',
        'resolved_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo(DriverProfile::class, 'driver_profile_id');
    }
}
