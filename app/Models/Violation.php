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
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function profile()
    {
        return $this->belongsTo(DriverProfile::class, 'driver_profile_id');
    }
}
